<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bulan;
use App\Models\Bebas;
use App\Models\Period;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Majors;
use App\Models\Month;
use App\Models\Setting;
use App\Models\Debit;
use App\Models\Kredit;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\ExcelExport;

class ReportController extends Controller {

    // Laporan Total Keuangan
    public function index(Request $request) {
        $periods = Period::orderByDesc('period_id')->get();
        $period  = Period::active()->first();

        $params = [];
        if ($request->filled('ds')) $params['date_start'] = $request->ds;
        if ($request->filled('de')) $params['date_end']   = $request->de;

        $bulanQuery = Bulan::where('bulan_status', 1);
        $bebasQuery = Bebas::query();
        $kreditQuery = Kredit::query();
        $debitQuery  = Debit::query();

        if (!empty($params['date_start']) && !empty($params['date_end'])) {
            $bulanQuery->whereBetween('bulan_date_pay', [$params['date_start'], $params['date_end']]);
            $kreditQuery->whereBetween('kredit_date', [$params['date_start'], $params['date_end']]);
            $debitQuery->whereBetween('debit_date', [$params['date_start'], $params['date_end']]);
        }

        $totalBulan  = $bulanQuery->sum('bulan_bill');
        $totalBebas  = $bebasQuery->sum('bebas_total_pay');
        $totalKredit = $kreditQuery->sum('kredit_value'); // pengeluaran
        $totalDebit  = $debitQuery->sum('debit_value');   // pemasukan

        $pemasukan  = $totalBulan + $totalBebas + $totalDebit;
        $pengeluaran= $totalKredit;
        $saldo      = $pemasukan - $pengeluaran;

        return $this->render('report.index', compact(
            'periods', 'period', 'totalBulan', 'totalBebas',
            'totalKredit', 'totalDebit', 'pemasukan', 'pengeluaran', 'saldo'
        ));
    }

    // Export Laporan Total Keuangan → Excel (.xlsx)
    public function exportKeuangan(Request $request) {
        $ds = $request->ds;
        $de = $request->de;

        $bulanQuery = Bulan::with(['student.class', 'payment.pos', 'payment.period', 'month'])
            ->where('bulan_status', 1);
        $bebasQuery = \App\Models\BebasPay::with(['bebas.student.class', 'bebas.payment.pos', 'bebas.payment.period']);
        $debitQuery  = Debit::query();
        $kreditQuery = Kredit::query();

        if ($ds && $de) {
            $bulanQuery->whereBetween('bulan_date_pay', [$ds, $de]);
            $bebasQuery->whereBetween('bebas_pay_input_date', [$ds, $de]);
            $debitQuery->whereBetween('debit_date', [$ds, $de]);
            $kreditQuery->whereBetween('kredit_date', [$ds, $de]);
        }

        $bulans    = $bulanQuery->get();
        $bebasPays = $bebasQuery->get();
        $debits    = $debitQuery->get();
        $kredits   = $kreditQuery->get();
        $school    = Setting::getValue(1);
        $periode   = ($ds && $de)
            ? \Carbon\Carbon::parse($ds)->locale('id')->isoFormat('D MMMM Y') . ' s/d ' . \Carbon\Carbon::parse($de)->locale('id')->isoFormat('D MMMM Y')
            : 'Semua Periode';

        $headers = ['NO','PEMBAYARAN','NAMA SISWA','KELAS','TANGGAL','PENERIMAAN','PENGELUARAN','KETERANGAN'];
        $data    = [];
        $no      = 1;

        foreach ($bulans as $b) {
            $data[] = [
                $no++,
                ($b->payment->pos->pos_name ?? '') . ' - T.P ' . ($b->payment->period->period_start ?? '') . '/' . ($b->payment->period->period_end ?? '') . ' (' . ($b->month->month_name ?? '') . ')',
                $b->student->student_full_name ?? '',
                $b->student->class->class_name ?? '',
                $b->bulan_date_pay,
                $b->bulan_bill,
                0,
                $b->bulan_pay_desc ?? '',
            ];
        }
        foreach ($bebasPays as $bp) {
            $bebas = $bp->bebas;
            $data[] = [
                $no++,
                ($bebas->payment->pos->pos_name ?? '') . ' - T.P ' . ($bebas->payment->period->period_start ?? '') . '/' . ($bebas->payment->period->period_end ?? ''),
                $bebas->student->student_full_name ?? '',
                $bebas->student->class->class_name ?? '',
                $bp->bebas_pay_input_date,
                $bp->bebas_pay_bill,
                0,
                $bp->bebas_pay_desc ?? '',
            ];
        }
        foreach ($debits as $d) {
            $data[] = [$no++, 'Pemasukan Lain-lain', '', '', $d->debit_date, $d->debit_value, 0, $d->debit_desc ?? ''];
        }
        foreach ($kredits as $k) {
            $data[] = [$no++, 'Pengeluaran', '', '', $k->kredit_date, 0, $k->kredit_value, $k->kredit_desc ?? ''];
        }

        $filename = 'Laporan_Keuangan_' . date('Y-m-d') . '.xls';

        return (new \App\Helpers\ExcelExport('Laporan Keuangan'))
            ->addSheet($school . ' — ' . $periode, $headers, $data, ['F' => 'number', 'G' => 'number'])
            ->download($filename);
    }

    public function cetak(Request $request) {
        $query = Bulan::with(['student.class', 'student.majors', 'payment.pos', 'payment.period', 'month'])
            ->where('bulan_status', 1);
        if ($request->filled('period_id'))
            $query->whereHas('payment', fn($q) => $q->where('period_period_id', $request->period_id));
        if ($request->filled('class_id'))
            $query->whereHas('student', fn($q) => $q->where('class_class_id', $request->class_id));
        if ($request->filled('date_start') && $request->filled('date_end'))
            $query->whereBetween('bulan_date_pay', [$request->date_start, $request->date_end]);

        $payouts = $query->orderBy('bulan_date_pay')->get();
        $setting = ['school' => Setting::getValue(1), 'address' => Setting::getValue(2)];
        $pdf = Pdf::loadView('report.cetak', compact('payouts', 'setting', 'request'))
                  ->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-pembayaran.pdf');
    }

    // Laporan Per-Kelas (matrix per bulan)
    public function bill(Request $request) {
        $periods = Period::orderByDesc('period_id')->get();
        $majorsList = Majors::orderBy('majors_name')->get();

        $classesQuery = StudentClass::query();
        if ($request->filled('k')) {
            $classesQuery->whereHas('students', fn($q) => $q->where('majors_majors_id', $request->k));
        }
        $classes = $classesQuery->orderBy('class_name')->get();

        $result = null;

        if ($request->filled('p')) {
            // Urutan bulan: Juli - Juni
            $monthOrder = ['Juli','Agustus','September','Oktober','November','Desember','Januari','Februari','Maret','April','Mei','Juni'];
            $allMonths = Month::all()->keyBy('month_name');
            $sortedMonths = [];
            foreach ($monthOrder as $name) {
                if (isset($allMonths[$name])) $sortedMonths[] = $allMonths[$name];
            }

            $studentQuery = Student::with(['class', 'majors'])->active()
                ->whereHas('payments', fn($q) => $q->whereHas('payment', fn($q2) => $q2->where('period_period_id', $request->p)));

            if ($request->filled('c')) {
                $studentQuery->where('class_class_id', $request->c);
            } elseif ($request->filled('k')) {
                $studentQuery->where('majors_majors_id', $request->k);
            }

            $students = $studentQuery->orderBy('class_class_id')->orderBy('student_full_name')->get();

            $rows = [];
            $grandDibayar = 0;
            $grandKekurangan = 0;

            foreach ($students as $student) {
                $bulans = Bulan::with(['payment.pos', 'month'])
                    ->where('student_student_id', $student->student_id)
                    ->whereHas('payment', fn($q) => $q->where('period_period_id', $request->p)
                        ->where('payment_type', 'BULAN'))
                    ->get();

                $bebas = Bebas::with('payment.pos')
                    ->where('student_student_id', $student->student_id)
                    ->whereHas('payment', fn($q) => $q->where('period_period_id', $request->p)
                        ->where('payment_type', 'BEBAS'))
                    ->get();

                $monthCells = [];
                $totalDibayar = 0;
                $totalTagihan = 0;
                foreach ($sortedMonths as $m) {
                    $b = $bulans->firstWhere('month_month_id', $m->month_id);
                    if ($b) {
                        $totalTagihan += $b->bulan_bill;
                        if ($b->bulan_status) $totalDibayar += $b->bulan_bill;
                        $monthCells[$m->month_id] = $b;
                    } else {
                        $monthCells[$m->month_id] = null;
                    }
                }

                $bebasTotal = 0;
                foreach ($bebas as $bb) {
                    $totalTagihan += $bb->bebas_bill;
                    $totalDibayar += $bb->bebas_total_pay;
                    $bebasTotal += $bb->bebas_total_pay;
                }

                $kekurangan = $totalTagihan - $totalDibayar;
                $grandDibayar += $totalDibayar;
                $grandKekurangan += $kekurangan;

                $rows[] = [
                    'student' => $student,
                    'months'  => $monthCells,
                    'bebas'   => $bebasTotal,
                    'total_dibayar' => $totalDibayar,
                    'kekurangan'    => $kekurangan,
                ];
            }

            $result = [
                'months' => $sortedMonths,
                'rows'   => $rows,
                'grand_dibayar' => $grandDibayar,
                'grand_kekurangan' => $grandKekurangan,
            ];
        }

        return $this->render('report.bill', compact('periods', 'majorsList', 'classes', 'result'));
    }

    // Export Laporan Per-Kelas ke CSV
    public function billExport(Request $request) {
        $periods = Period::orderByDesc('period_id')->get();

        $monthOrder = ['Juli','Agustus','September','Oktober','November','Desember','Januari','Februari','Maret','April','Mei','Juni'];
        $allMonths = Month::all()->keyBy('month_name');
        $sortedMonths = [];
        foreach ($monthOrder as $name) {
            if (isset($allMonths[$name])) $sortedMonths[] = $allMonths[$name];
        }

        $studentQuery = Student::with(['class','majors'])->active()
            ->whereHas('payments', fn($q) => $q->whereHas('payment', fn($q2) => $q2->where('period_period_id', $request->p)));
        if ($request->filled('c')) $studentQuery->where('class_class_id', $request->c);
        elseif ($request->filled('k')) $studentQuery->where('majors_majors_id', $request->k);
        $students = $studentQuery->orderBy('class_class_id')->orderBy('student_full_name')->get();

        // Build header & data
        $hdr = ['No', 'Kelas', 'NIS', 'Nama'];
        foreach ($sortedMonths as $m) $hdr[] = $m->month_name;
        $hdr[] = 'Total Dibayar';
        $hdr[] = 'Kekurangan';

        $data = [];
        $no   = 1;
        foreach ($students as $student) {
            $bulans = Bulan::where('student_student_id', $student->student_id)
                ->whereHas('payment', fn($q) => $q->where('period_period_id', $request->p)->where('payment_type','BULAN'))
                ->get();

            $row = [$no++, $student->class->class_name ?? '-', $student->student_nis, $student->student_full_name];
            $totalDibayar = 0; $totalTagihan = 0;
            foreach ($sortedMonths as $m) {
                $b = $bulans->firstWhere('month_month_id', $m->month_id);
                if ($b) {
                    $totalTagihan += $b->bulan_bill;
                    if ($b->bulan_status) $totalDibayar += $b->bulan_bill;
                    $row[] = $b->bulan_status ? 'LUNAS' : $b->bulan_bill;
                } else {
                    $row[] = '';
                }
            }
            $row[] = $totalDibayar;
            $row[] = $totalTagihan - $totalDibayar;
            $data[] = $row;
        }

        $period = \App\Models\Period::find($request->p);
        $sheetName = 'Lap Per-Kelas ' . ($period ? $period->period_start.'/'.$period->period_end : '');

        return (new \App\Helpers\ExcelExport('Laporan Per-Kelas'))
            ->addSheet($sheetName, $hdr, $data)
            ->download('Laporan_Per_Kelas_' . date('Y-m-d') . '.xls');
    }

    // Export Rekapitulasi Pembayaran Per-Kelas ke CSV (pengganti PHPExcel report_bill_detail)
    public function billDetailExport(Request $request) {
        $periodId = $request->p;
        $classId  = $request->c;
        $majorsId = $request->k;

        $studentQuery = Student::with(['class','majors'])
            ->where('student_status', 1)
            ->when($periodId, fn($q) => $q->whereHas('payments', fn($q2) => $q2->whereHas('payment', fn($q3) => $q3->where('period_period_id', $periodId))))
            ->when($classId,  fn($q) => $q->where('class_class_id', $classId))
            ->when($majorsId, fn($q) => $q->where('majors_majors_id', $majorsId))
            ->orderBy('student_full_name');

        $students = $studentQuery->get();
        $period   = Period::find($periodId);
        $school   = Setting::getValue(1);

        $headers = ['NO','NIS','NAMA','KELAS','JENIS PEMBAYARAN','TAGIHAN','DIBAYAR','SISA','STATUS'];
        $data    = [];
        $no      = 1;

        foreach ($students as $student) {
            $bulans = \App\Models\Bulan::with(['payment.pos','month'])
                ->where('student_student_id', $student->student_id)
                ->when($periodId, fn($q) => $q->whereHas('payment', fn($q2) => $q2->where('period_period_id', $periodId)))
                ->get();

            $bebases = \App\Models\Bebas::with(['payment.pos'])
                ->where('student_student_id', $student->student_id)
                ->when($periodId, fn($q) => $q->whereHas('payment', fn($q2) => $q2->where('period_period_id', $periodId)))
                ->get();

            foreach ($bulans as $b) {
                $data[] = [
                    $no++,
                    $student->student_nis,
                    $student->student_full_name,
                    $student->class->class_name ?? '',
                    ($b->payment->pos->pos_name ?? '') . ' - ' . ($b->month->month_name ?? ''),
                    $b->bulan_bill,
                    $b->bulan_status ? $b->bulan_bill : 0,
                    $b->bulan_status ? 0 : $b->bulan_bill,
                    $b->bulan_status ? 'Lunas' : 'Belum Lunas',
                ];
            }
            foreach ($bebases as $b) {
                $sisa = $b->bebas_bill - $b->bebas_total_pay;
                $data[] = [
                    $no++,
                    $student->student_nis,
                    $student->student_full_name,
                    $student->class->class_name ?? '',
                    $b->payment->pos->pos_name ?? '',
                    $b->bebas_bill,
                    $b->bebas_total_pay,
                    $sisa,
                    $sisa <= 0 ? 'Lunas' : 'Belum Lunas',
                ];
            }
        }

        $sheetName = 'Rekapitulasi ' . ($period ? $period->period_start.'/'.$period->period_end : 'Semua');

        return (new \App\Helpers\ExcelExport('Rekapitulasi Pembayaran'))
            ->addSheet($sheetName, $headers, $data, ['F'=>'number','G'=>'number','H'=>'number'])
            ->download('Rekapitulasi_Pembayaran_' . date('Y-m-d') . '.xls');
    }

}
