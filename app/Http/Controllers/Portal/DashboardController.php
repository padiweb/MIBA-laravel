<?php
namespace App\Http\Controllers\Portal;

use App\Models\Student;
use App\Models\Bulan;
use App\Models\Bebas;
use App\Models\Period;
use App\Models\Information;
use App\Models\Holiday;

class DashboardController extends PortalController {

    public function index() {
        $student = Student::with(['class','majors'])->findOrFail(session('student_id'));
        $period  = Period::active()->first();

        $bulanQuery = Bulan::with(['month','payment.pos','payment.period'])
            ->where('student_student_id', $student->student_id)
            ->where('bulan_status', 0);
        if ($period) {
            $bulanQuery->whereHas('payment', fn($q) => $q->where('period_period_id', $period->period_id));
        }
        $bulanUnpaid = $bulanQuery->orderBy('month_month_id')->get();
        $totalBulan = $bulanUnpaid->sum('bulan_bill');

        $bebasQuery = Bebas::with(['payment.pos','payment.period'])
            ->where('student_student_id', $student->student_id);
        if ($period) {
            $bebasQuery->whereHas('payment', fn($q) => $q->where('period_period_id', $period->period_id));
        }
        $bebasList = $bebasQuery->get();
        $totalBebas    = $bebasList->sum('bebas_bill');
        $totalBebasPay = $bebasList->sum('bebas_total_pay');

        $infos = Information::where('information_publish', 1)
            ->orderByDesc('information_id')->limit(5)->get();

        $holidays = Holiday::orderBy('date')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->limit(5)->get();

        return $this->render('portal.dashboard', compact(
            'student', 'period', 'bulanUnpaid', 'totalBulan',
            'bebasList', 'totalBebas', 'totalBebasPay', 'infos', 'holidays'
        ));
    }
}
