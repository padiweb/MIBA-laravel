<?php
namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Bulan;
use App\Models\Bebas;
use App\Models\Period;

class PayoutController extends PortalController {

    // Cek Pembayaran Siswa - tagihan bulanan & bebas untuk tahun pelajaran tertentu
    public function index(Request $request) {
        $student = Student::with(['class','majors'])->findOrFail(session('student_id'));
        $periods = Period::orderByDesc('period_id')->get();

        $periodId = $request->input('n', Period::active()->first()->period_id ?? null);

        $bulans = Bulan::with(['month','payment.pos','payment.period'])
            ->where('student_student_id', $student->student_id)
            ->when($periodId, fn($q) => $q->whereHas('payment', fn($q2) => $q2->where('period_period_id', $periodId)))
            ->orderBy('month_month_id')
            ->get();

        $bebasList = Bebas::with(['payment.pos','payment.period'])
            ->where('student_student_id', $student->student_id)
            ->when($periodId, fn($q) => $q->whereHas('payment', fn($q2) => $q2->where('period_period_id', $periodId)))
            ->get();

        return $this->render('portal.payout', compact('student','periods','periodId','bulans','bebasList'));
    }
}
