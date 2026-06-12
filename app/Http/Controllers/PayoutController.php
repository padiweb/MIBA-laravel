<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bulan;
use App\Models\Bebas;
use App\Models\BebasPay;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Month;
use App\Models\Period;
use App\Models\Letter;
use App\Models\LogTrx;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class PayoutController extends Controller {

    // Halaman utama — cari siswa berdasarkan NIS (sama seperti payout_list CI3)
    public function index(Request $request) {
        $periods = Period::orderByDesc('period_id')->get();
        $student = null;
        $paymentsList = collect();
        $bebasList = collect();
        $lastTrx = collect();
        $cash = 0;
        $cashb = 0;

        if ($request->filled('r')) {
            $student = Student::with(['class', 'majors'])
                ->where('student_nis', $request->r)
                ->first();

            if ($student) {
                // Daftar jenis pembayaran bulanan untuk tahun pelajaran terpilih
                $paymentsQuery = Payment::with(['pos', 'period'])
                    ->where('payment_type', 'bulanan')
                    ->whereHas('bulans', fn($q) => $q->where('student_student_id', $student->student_id));

                if ($request->filled('n')) {
                    $paymentsQuery->where('period_period_id', $request->n);
                }
                $paymentsList = $paymentsQuery->get();

                // Daftar pembayaran bebas
                $bebasQuery = Bebas::with(['payment.pos', 'payment.period'])
                    ->where('student_student_id', $student->student_id);
                if ($request->filled('n')) {
                    $bebasQuery->whereHas('payment', fn($q) => $q->where('period_period_id', $request->n));
                }
                $bebasList = $bebasQuery->get();

                // Transaksi terakhir (3 terbaru)
                $lastTrx = LogTrx::with(['bulan.payment.pos', 'bulan.payment.period', 'bulan.month',
                                          'bebasPay.bebas.payment.pos', 'bebasPay.bebas.payment.period'])
                    ->where('student_student_id', $student->student_id)
                    ->orderByDesc('log_trx_id')
                    ->limit(3)
                    ->get();

                // Total cashback (untuk kalkulator) - total dibayar hari ini
                $today = now()->format('Y-m-d');
                $cash = Bulan::where('student_student_id', $student->student_id)
                    ->where('bulan_status', 1)
                    ->whereDate('bulan_date_pay', $today)
                    ->sum('bulan_bill');
                $cashb = BebasPay::whereHas('bebas', fn($q) => $q->where('student_student_id', $student->student_id))
                    ->whereDate('bebas_pay_input_date', $today)
                    ->sum('bebas_pay_bill');
            }
        }

        return $this->render('payout.index', compact(
            'periods', 'student', 'paymentsList', 'bebasList', 'lastTrx', 'cash', 'cashb'
        ));
    }

    // Halaman detail pembayaran per jenis (bulanan)
    public function bayar($payment_id, $student_id) {
        $payment = Payment::with(['pos', 'period'])->findOrFail($payment_id);
        $student = Student::with(['class', 'majors'])->findOrFail($student_id);
        $bulans  = Bulan::with('month')
            ->where('payment_payment_id', $payment_id)
            ->where('student_student_id', $student_id)
            ->orderBy('month_month_id')
            ->get();

        return $this->render('payout.bayar', compact('payment', 'student', 'bulans'));
    }

    // Bayar satu bulan
    public function pay($payment_id, $student_id, $bulan_id) {
        $bulan = Bulan::with(['student', 'payment.period', 'month'])->findOrFail($bulan_id);

        $nofull = $this->generateNomorBukti();

        $bulan->update([
            'bulan_status'      => 1,
            'bulan_number_pay'  => $nofull,
            'bulan_date_pay'    => now()->format('Y-m-d'),
            'bulan_last_update' => now(),
            'user_user_id'      => session('user_id'),
        ]);

        LogTrx::create([
            'student_student_id'     => $student_id,
            'bulan_bulan_id'         => $bulan_id,
            'bebas_pay_bebas_pay_id' => null,
            'log_trx_input_date'     => now(),
            'log_trx_last_update'    => now(),
        ]);

        $this->writeLog('PAY', 'payout', 'Bayar bulan: ' . $bulan->month->month_name . ' | Siswa: ' . $bulan->student->student_full_name);

        return redirect()
            ->route('payout.bayar', [$payment_id, $student_id])
            ->with('success', 'Pembayaran bulan ' . $bulan->month->month_name . ' berhasil!');
    }

    // Batal bayar satu bulan (= not_pay di CI3)
    public function unpay($payment_id, $student_id, $bulan_id) {
        $bulan = Bulan::with(['month'])->findOrFail($bulan_id);

        $bulan->update([
            'bulan_status'      => 0,
            'bulan_number_pay'  => null,
            'bulan_date_pay'    => null,
            'bulan_last_update' => now(),
            'user_user_id'      => null,
        ]);

        LogTrx::where('bulan_bulan_id', $bulan_id)
            ->where('student_student_id', $student_id)
            ->delete();

        return redirect()
            ->route('payout.bayar', [$payment_id, $student_id])
            ->with('success', 'Pembayaran dibatalkan');
    }

    // Update keterangan bayar
    public function updateDesc(Request $request) {
        Bulan::findOrFail($request->bulan_id)
            ->update(['bulan_pay_desc' => $request->bulan_pay_desc]);

        return redirect()
            ->route('payout.bayar', [$request->payment_id, $request->student_id])
            ->with('success', 'Keterangan diupdate');
    }

    // ===== Pembayaran Bebas (Angsuran/Cicilan) =====
    public function payoutBebas(Request $request) {
        $request->validate([
            'bebas_id'              => 'required|exists:bebas,bebas_id',
            'bebas_pay_bill'        => 'required|numeric|min:1',
            'bebas_pay_desc'        => 'required',
            'student_student_id'    => 'required',
            'payment_payment_id'    => 'required',
            'student_nis'           => 'required',
        ]);

        $bebas = Bebas::findOrFail($request->bebas_id);
        $sisa  = $bebas->bebas_bill - $bebas->bebas_total_pay;

        if ($request->bebas_pay_bill > $sisa || $request->bebas_pay_bill == 0) {
            return redirect()
                ->route('payout.index', ['n' => $bebas->payment->period_period_id ?? null, 'r' => $request->student_nis])
                ->with('failed', 'Pembayaran yang anda masukkan melebihi total tagihan!!!');
        }

        $nofull = $this->generateNomorBukti();

        $bebasPay = BebasPay::create([
            'bebas_bebas_id'        => $bebas->bebas_id,
            'bebas_pay_number'      => $nofull,
            'bebas_pay_bill'        => $request->bebas_pay_bill,
            'bebas_pay_desc'        => $request->bebas_pay_desc,
            'user_user_id'          => session('user_id'),
            'bebas_pay_input_date'  => now()->format('Y-m-d'),
            'bebas_pay_last_update' => now(),
        ]);

        $bebas->increment('bebas_total_pay', $request->bebas_pay_bill);
        $bebas->update(['bebas_last_update' => now()]);

        LogTrx::create([
            'student_student_id'     => $request->student_student_id,
            'bulan_bulan_id'         => null,
            'bebas_pay_bebas_pay_id' => $bebasPay->bebas_pay_id,
            'log_trx_input_date'     => now(),
            'log_trx_last_update'    => now(),
        ]);

        $this->writeLog('PAY', 'payout', 'Bayar angsuran bebas: Rp ' . number_format($request->bebas_pay_bill,0,',','.'));

        return redirect()
            ->route('payout.index', ['n' => $bebas->payment->period_period_id ?? null, 'r' => $request->student_nis])
            ->with('success', 'Pembayaran Tagihan berhasil');
    }

    // ===== Cetak Bukti / Kwitansi Pembayaran (per tanggal) =====
    public function cetakBukti(Request $request) {
        $request->validate([
            'n' => 'required', // period_id
            'r' => 'required', // nis
            'd' => 'required', // tanggal
        ]);

        $student = Student::with(['class', 'majors'])
            ->where('student_nis', $request->r)->firstOrFail();
        $period  = Period::find($request->n);

        $bulans = Bulan::with(['payment.pos', 'payment.period', 'month'])
            ->where('student_student_id', $student->student_id)
            ->where('bulan_status', 1)
            ->whereDate('bulan_date_pay', $request->d)
            ->whereHas('payment', fn($q) => $q->where('period_period_id', $request->n))
            ->get();

        $free = BebasPay::with(['bebas.payment.pos', 'bebas.payment.period'])
            ->whereHas('bebas', fn($q) => $q->where('student_student_id', $student->student_id)
                ->whereHas('payment', fn($q2) => $q2->where('period_period_id', $request->n)))
            ->whereDate('bebas_pay_input_date', $request->d)
            ->get();

        $summonth = $bulans->sum('bulan_bill');
        $sumbeb   = $free->sum('bebas_pay_bill');

        $setting = [
            'school'   => Setting::getValue(1),
            'address'  => Setting::getValue(2),
            'phone'    => Setting::getValue(3),
            'district' => Setting::getValue(4),
            'city'     => Setting::getValue(5),
        ];

        $petugas = session('user_fullname');

        $pdf = Pdf::loadView('payout.cetak', compact(
            'student', 'period', 'bulans', 'free', 'summonth', 'sumbeb', 'setting', 'request', 'petugas'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('Cetak_Struk_' . $student->student_full_name . '_' . $request->d . '.pdf');
    }

    // ===== Cetak semua tagihan (printBill) =====
    public function cetakTagihan(Request $request) {
        $student = Student::with(['class', 'majors'])
            ->where('student_nis', $request->r)->first();
        $period  = Period::find($request->n);

        $bulans = Bulan::with(['payment.pos', 'payment.period', 'month'])
            ->where('student_student_id', $student->student_id)
            ->whereHas('payment', fn($q) => $q->where('period_period_id', $request->n))
            ->get();

        $bebas = Bebas::with(['payment.pos', 'payment.period'])
            ->where('student_student_id', $student->student_id)
            ->whereHas('payment', fn($q) => $q->where('period_period_id', $request->n))
            ->get();

        $setting = [
            'school'  => Setting::getValue(1),
            'address' => Setting::getValue(2),
            'city'    => Setting::getValue(5),
        ];

        $pdf = Pdf::loadView('payout.cetak_tagihan', compact('student', 'period', 'bulans', 'bebas', 'setting'))
                  ->setPaper('a4');
        return $pdf->stream('tagihan-' . $student->student_nis . '.pdf');
    }

    // Cetak bukti per bulan (legacy - dari riwayat transaksi)
    public function cetak($bulan_id) {
        $bulan = Bulan::with([
            'student.class', 'student.majors',
            'payment.pos', 'payment.period',
            'month', 'user'
        ])->findOrFail($bulan_id);

        $setting = [
            'school'  => Setting::getValue(1),
            'address' => Setting::getValue(2),
            'phone'   => Setting::getValue(3),
            'city'    => Setting::getValue(5),
        ];

        $student = $bulan->student;
        $period  = $bulan->payment->period;
        $bulans  = collect([$bulan]);
        $free    = collect();
        $summonth = $bulan->bulan_bill;
        $sumbeb   = 0;
        $petugas  = $bulan->user->user_full_name ?? session('user_fullname');
        $request  = request();
        $request->merge(['d' => $bulan->bulan_date_pay]);

        $pdf = Pdf::loadView('payout.cetak', compact(
            'student', 'period', 'bulans', 'free', 'summonth', 'sumbeb', 'setting', 'request', 'petugas'
        ))->setPaper('a4', 'portrait');
        return $pdf->stream('bukti-' . $bulan_id . '.pdf');
    }

    private function generateNomorBukti(): string {
        $letter = Letter::orderByDesc('letter_id')->first();

        if (!$letter || $letter->letter_year < date('Y')) {
            $nomor = 1;
        } else {
            $nomor = intval($letter->letter_number) + 1;
        }

        Letter::create([
            'letter_number' => sprintf('%05d', $nomor),
            'letter_month'  => date('m'),
            'letter_year'   => date('Y'),
        ]);

        return date('Y') . date('m') . sprintf('%05d', $nomor);
    }
}
