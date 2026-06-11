<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Bulan;
use App\Models\Debit;
use App\Models\Kredit;
use App\Models\Period;
use App\Models\Information;

class DashboardController extends Controller {

    public function index() {
        $period    = Period::active()->first();
        $totalSiswa      = Student::active()->count();
        $totalSiswaBayar = Bulan::where('bulan_status', 1)
            ->when($period, fn($q) => $q->whereHas('payment',
                fn($q2) => $q2->where('period_period_id', $period->period_id)))
            ->distinct('student_student_id')->count('student_student_id');
        $totalDebit  = Debit::whereMonth('debit_date', now()->month)->sum('debit_value');
        $totalKredit = Kredit::whereMonth('kredit_date', now()->month)->sum('kredit_value');
        $infos       = Information::where('information_publish', 1)
            ->orderByDesc('information_id')->limit(5)->get();

        return $this->render('dashboard.index', compact(
            'totalSiswa', 'totalSiswaBayar', 'totalDebit', 'totalKredit', 'infos', 'period'
        ));
    }
}
