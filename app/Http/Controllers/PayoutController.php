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
use App\Models\LogTrx;
use Barryvdh\DomPDF\Facade\Pdf;

class PayoutController extends Controller {

    public function index(Request $request) {
        $query = Bulan::with(['student', 'payment.pos', 'month', 'user']);

        if ($request->filled('n'))
            $query->whereHas('student', fn($q) =>
                $q->where('student_full_name', 'like', '%'.$request->n.'%')
                  ->orWhere('student_nis', 'like', '%'.$request->n.'%'));
        if ($request->filled('period_id'))
            $query->whereHas('payment', fn($q) =>
                $q->where('period_period_id', $request->period_id));
        if ($request->filled('date_start') && $request->filled('date_end'))
            $query->whereBetween('bulan_date_pay', [$request->date_start, $request->date_end]);

        $payouts  = $query->orderByDesc('bulan_id')->paginate(20)->withQueryString();
        $periods  = Period::orderByDesc('period_id')->get();
        return $this->render('payout.index', compact('payouts', 'periods'));
    }

    public function create() {
        $students = Student::active()->orderBy('student_full_name')->get();
        $payments = Payment::with(['pos', 'period'])->get();
        $months   = Month::all();
        return $this->render('payout.form', compact('students', 'payments', 'months'));
    }

    public function store(Request $request) {
        $request->validate([
            'student_student_id'  => 'required',
            'payment_payment_id'  => 'required',
            'month_month_id'      => 'required',
            'bulan_bill'          => 'required|numeric',
        ]);

        $data = $request->except('_token');
        $data['bulan_status']       = 1;
        $data['user_user_id']       = session('user_id');
        $data['bulan_input_date']   = now();
        $data['bulan_last_update']  = now();

        $bulan = Bulan::create($data);
        LogTrx::create([
            'student_student_id'   => $request->student_student_id,
            'bulan_bulan_id'       => $bulan->bulan_id,
            'log_trx_input_date'   => now(),
            'log_trx_last_update'  => now(),
        ]);
        $this->writeLog('ADD', 'payout', 'Bayar bulan: ' . $bulan->bulan_id);
        return redirect()->route('payout.index')->with('success', 'Pembayaran berhasil disimpan');
    }

    public function cetak($id) {
        $payout = Bulan::with(['student.class', 'student.majors', 'payment.pos', 'month', 'user'])->findOrFail($id);
        $setting = [
            'school'   => \App\Models\Setting::getValue(1),
            'address'  => \App\Models\Setting::getValue(2),
            'logo'     => \App\Models\Setting::getValue(6),
        ];
        $pdf = Pdf::loadView('payout.cetak', compact('payout', 'setting'))
                  ->setPaper('a5', 'landscape');
        return $pdf->stream('bukti-pembayaran-' . $id . '.pdf');
    }

    public function destroy($id) {
        Bulan::findOrFail($id)->delete();
        return redirect()->route('payout.index')->with('success', 'Data pembayaran dihapus');
    }
}
