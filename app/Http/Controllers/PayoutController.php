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

    // Halaman utama — cari siswa berdasarkan NIS
    public function index(Request $request) {
        $periods = Period::orderByDesc('period_id')->get();
        $student = null;
        $bulanData = [];
        $bebasData = [];
        $totalTagihan = 0;
        $totalBayar = 0;

        if ($request->filled('r') && $request->filled('n')) {
            $student = Student::with(['class', 'majors'])
                ->where('student_nis', $request->r)
                ->first();

            if ($student) {
                // Ambil semua payment bulanan untuk tahun pelajaran ini
                $payments = Payment::with(['pos', 'period'])
                    ->where('period_period_id', $request->n)
                    ->get();

                foreach ($payments as $payment) {
                    $bulans = Bulan::with('month')
                        ->where('payment_payment_id', $payment->payment_id)
                        ->where('student_student_id', $student->student_id)
                        ->orderBy('month_month_id')
                        ->get();

                    if ($bulans->count() > 0) {
                        $bulanData[] = [
                            'payment'  => $payment,
                            'bulans'   => $bulans,
                            'total'    => $bulans->sum('bulan_bill'),
                            'sudah_bayar' => $bulans->where('bulan_status', 1)->sum('bulan_bill'),
                        ];
                        $totalTagihan += $bulans->sum('bulan_bill');
                        $totalBayar   += $bulans->where('bulan_status', 1)->sum('bulan_bill');
                    }
                }

                // Bebas (non-bulanan)
                $bebasData = Bebas::with(['payment.pos', 'bebasPays'])
                    ->where('student_student_id', $student->student_id)
                    ->whereHas('payment', fn($q) => $q->where('period_period_id', $request->n))
                    ->get();
            }
        }

        return $this->render('payout.index', compact(
            'periods', 'student', 'bulanData', 'bebasData',
            'totalTagihan', 'totalBayar'
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

        // Generate nomor bukti bayar
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

    // Batal bayar satu bulan
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

    // Cetak bukti per bulan (PDF)
    public function cetak($bulan_id) {
        $bulan = Bulan::with([
            'student.class', 'student.majors',
            'payment.pos', 'payment.period',
            'month', 'user'
        ])->findOrFail($bulan_id);

        $setting = [
            'school'  => Setting::getValue(1),
            'address' => Setting::getValue(2),
            'logo'    => Setting::getValue(6),
        ];

        $pdf = Pdf::loadView('payout.cetak', compact('bulan', 'setting'))
                  ->setPaper('a5', 'landscape');
        return $pdf->stream('bukti-' . $bulan_id . '.pdf');
    }

    // Cetak semua tagihan siswa (PDF)
    public function cetakTagihan(Request $request) {
        $student = Student::with(['class', 'majors'])
            ->where('student_nis', $request->r)->first();
        $period  = Period::find($request->n);

        $bulans = Bulan::with(['payment.pos', 'payment.period', 'month'])
            ->where('student_student_id', $student->student_id)
            ->whereHas('payment', fn($q) => $q->where('period_period_id', $request->n))
            ->get();

        $setting = [
            'school'  => Setting::getValue(1),
            'address' => Setting::getValue(2),
        ];

        $pdf = Pdf::loadView('payout.cetak_tagihan', compact('student', 'period', 'bulans', 'setting'))
                  ->setPaper('a4');
        return $pdf->stream('tagihan-' . $student->student_nis . '.pdf');
    }

    private function generateNomorBukti(): string {
        $letter = Letter::orderByDesc('letter_id')->first();

        if (!$letter || $letter->letter_year < date('Y')) {
            $nomor = 1;
            Letter::create([
                'letter_number' => '00001',
                'letter_month'  => date('m'),
                'letter_year'   => date('Y'),
            ]);
        } else {
            $nomor = intval($letter->letter_number) + 1;
            Letter::create([
                'letter_number' => sprintf('%05d', $nomor),
                'letter_month'  => date('m'),
                'letter_year'   => date('Y'),
            ]);
        }

        return date('Y') . date('m') . sprintf('%05d', $nomor);
    }
}
