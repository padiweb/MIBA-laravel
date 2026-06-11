<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bulan;
use App\Models\Period;
use App\Models\Student;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller {
    public function index(Request $request) {
        $periods = Period::orderByDesc('period_id')->get();
        $period  = Period::active()->first();
        return $this->render('report.index', compact('periods', 'period'));
    }
    public function cetak(Request $request) {
        $query = Bulan::with(['student.class','student.majors','payment.pos','payment.period','month'])
            ->where('bulan_status', 1);
        if ($request->filled('period_id'))
            $query->whereHas('payment', fn($q) => $q->where('period_period_id', $request->period_id));
        if ($request->filled('class_id'))
            $query->whereHas('student', fn($q) => $q->where('class_class_id', $request->class_id));
        $payouts = $query->orderBy('bulan_date_pay')->get();
        $setting = ['school' => Setting::getValue(1), 'address' => Setting::getValue(2)];
        $pdf = Pdf::loadView('report.cetak', compact('payouts', 'setting', 'request'))
                  ->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-pembayaran.pdf');
    }
}
