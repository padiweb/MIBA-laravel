<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Period;
use App\Models\Pos;
use App\Models\Month;
use App\Models\Bulan;
use App\Models\Bebas;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Majors;

class PaymentController extends Controller {

    public function index(Request $request) {
        $query = Payment::with(['period', 'pos']);
        if ($request->filled('n'))
            $query->whereHas('pos', fn($q) => $q->where('pos_name', 'like', '%'.$request->n.'%'));
        if ($request->filled('period_id'))
            $query->where('period_period_id', $request->period_id);

        $payments = $query->orderByDesc('payment_id')->paginate(20)->withQueryString();
        $periods  = Period::orderByDesc('period_id')->get();
        return $this->render('payment.index', compact('payments', 'periods'));
    }

    public function create() {
        $this->requireSuperuser();
        $periods = Period::orderByDesc('period_id')->get();
        $poses   = Pos::orderBy('pos_name')->get();
        return $this->render('payment.form', compact('periods', 'poses'));
    }

    public function store(Request $request) {
        $this->requireSuperuser();
        $request->validate([
            'pos_pos_id'        => 'required',
            'period_period_id'  => 'required',
            'payment_type'      => 'required',
        ]);
        Payment::create(array_merge(
            $request->except('_token'),
            ['payment_input_date' => now(), 'payment_last_update' => now()]
        ));
        $this->writeLog('ADD', 'payment', 'Tambah jenis pembayaran');
        return redirect()->route('payment.index')->with('success', 'Jenis pembayaran ditambahkan');
    }

    public function edit($id) {
        $this->requireSuperuser();
        $payment = Payment::findOrFail($id);
        $periods = Period::orderByDesc('period_id')->get();
        $poses   = Pos::orderBy('pos_name')->get();
        return $this->render('payment.form', compact('payment', 'periods', 'poses'));
    }

    public function update(Request $request, $id) {
        $this->requireSuperuser();
        Payment::findOrFail($id)->update(array_merge(
            $request->except(['_token','_method']),
            ['payment_last_update' => now()]
        ));
        return redirect()->route('payment.index')->with('success', 'Jenis pembayaran diupdate');
    }

    public function destroy($id) {
        $this->requireSuperuser();
        Payment::findOrFail($id)->delete();
        return redirect()->route('payment.index')->with('success', 'Jenis pembayaran dihapus');
    }

    /* =========================================================
     *  TARIF PEMBAYARAN - BULANAN (view_bulan, add_payment_bulan*, edit_payment_bulan)
     * ========================================================= */

    // Halaman utama Atur Tarif Bulanan
    public function viewBulan(Request $request, $payment_id) {
        $this->requireSuperuser();
        $payment = Payment::with(['pos','period'])->findOrFail($payment_id);
        $classes = StudentClass::orderBy('class_name')->get();
        $majorsList = Majors::orderBy('majors_name')->get();

        $students = collect();
        if ($request->filled('pr') || $request->filled('k') || $request->has('q')) {
            $query = Student::with(['class','majors'])->active()
                ->whereHas('payments', fn($q) => $q->where('payment_payment_id', $payment_id));
            if ($request->filled('pr')) $query->where('class_class_id', $request->pr);
            if ($request->filled('k'))  $query->where('majors_majors_id', $request->k);
            $students = $query->orderBy('student_full_name')->get();
        }

        return $this->render('payment.view_bulan', compact('payment','classes','majorsList','students'));
    }

    // Form tambah tarif (mode: class|majors|student)
    public function addBulanForm(Request $request, $payment_id, $mode) {
        $this->requireSuperuser();
        $payment = Payment::with(['pos','period'])->findOrFail($payment_id);
        $classes = StudentClass::orderBy('class_name')->get();
        $majorsList = Majors::orderBy('majors_name')->get();
        $students = Student::active()->orderBy('student_full_name')->get();
        $months  = Month::orderBy('month_id')->get();

        return $this->render('payment.tarif_bulan_form', compact(
            'payment','classes','majorsList','students','months','mode'
        ));
    }

    // Simpan tarif bulanan (untuk mode class/majors/student)
    public function storeBulan(Request $request, $payment_id, $mode) {
        $this->requireSuperuser();
        $request->validate([
            'bulan_bill'  => 'required|array',
            'bulan_bill.*'=> 'required|numeric',
            'month_id'    => 'required|array',
        ]);

        // Tentukan target siswa
        $query = Student::active();
        if ($mode === 'class') {
            $request->validate(['class_id' => 'required']);
            $query->where('class_class_id', $request->class_id);
        } elseif ($mode === 'majors') {
            $request->validate(['class_id' => 'required', 'majors_id' => 'required']);
            $query->where('class_class_id', $request->class_id)
                  ->where('majors_majors_id', $request->majors_id);
        } else { // student
            $request->validate(['student_id' => 'required']);
            $query->where('student_id', $request->student_id);
        }
        $students = $query->get();

        if ($students->isEmpty()) {
            return back()->with('failed', 'Tidak ada siswa yang sesuai kriteria');
        }

        // Cek duplikat: jika salah satu siswa sudah punya bulan untuk payment ini, batalkan semua
        $existing = Bulan::where('payment_payment_id', $payment_id)
            ->whereIn('student_student_id', $students->pluck('student_id'))
            ->exists();

        if ($existing) {
            return redirect()->route('payment.viewBulan', $payment_id)
                ->with('failed', 'Duplikat Data — sebagian siswa sudah memiliki tarif untuk pembayaran ini');
        }

        $monthIds = $request->month_id;
        $bills    = $request->bulan_bill;

        foreach ($students as $student) {
            foreach ($monthIds as $i => $monthId) {
                Bulan::create([
                    'student_student_id' => $student->student_id,
                    'payment_payment_id' => $payment_id,
                    'month_month_id'     => $monthId,
                    'bulan_bill'         => $bills[$i],
                    'bulan_status'       => 0,
                    'bulan_input_date'   => now(),
                    'bulan_last_update'  => now(),
                ]);
            }
        }

        $this->writeLog('ADD', 'payment', 'Setting tarif bulanan untuk ' . $students->count() . ' siswa');
        return redirect()->route('payment.viewBulan', $payment_id)->with('success', 'Setting Tarif berhasil');
    }

    // Edit tarif bulanan per siswa
    public function editBulan($payment_id, $student_id) {
        $this->requireSuperuser();
        $payment = Payment::with(['pos','period'])->findOrFail($payment_id);
        $student = Student::with(['class','majors'])->findOrFail($student_id);
        $bulans  = Bulan::with('month')
            ->where('payment_payment_id', $payment_id)
            ->where('student_student_id', $student_id)
            ->orderBy('month_month_id')
            ->get();

        return $this->render('payment.edit_bulan', compact('payment','student','bulans'));
    }

    public function updateBulan(Request $request, $payment_id, $student_id) {
        $this->requireSuperuser();
        $bulanIds = $request->input('bulan_id', []);
        $bills    = $request->input('bulan_bill', []);

        foreach ($bulanIds as $i => $bulanId) {
            Bulan::where('bulan_id', $bulanId)->update([
                'bulan_bill'        => $bills[$i],
                'bulan_last_update' => now(),
            ]);
        }

        $this->writeLog('UPDATE', 'payment', 'Update tarif bulanan siswa ID ' . $student_id);
        return redirect()->route('payment.viewBulan', $payment_id)->with('success', 'Update Pembayaran berhasil');
    }

    /* =========================================================
     *  TARIF PEMBAYARAN - BEBAS (view_bebas, add_payment_bebas*, edit_payment_bebas)
     * ========================================================= */

    public function viewBebas(Request $request, $payment_id) {
        $this->requireSuperuser();
        $payment = Payment::with(['pos','period'])->findOrFail($payment_id);
        $classes = StudentClass::orderBy('class_name')->get();
        $majorsList = Majors::orderBy('majors_name')->get();

        $rows = collect();
        if ($request->filled('pr') || $request->filled('k') || $request->has('q')) {
            $query = Student::with(['class','majors'])->active();
            if ($request->filled('pr')) $query->where('class_class_id', $request->pr);
            if ($request->filled('k'))  $query->where('majors_majors_id', $request->k);
            $students = $query->orderBy('student_full_name')->get();

            foreach ($students as $student) {
                $bebas = Bebas::where('payment_payment_id', $payment_id)
                    ->where('student_student_id', $student->student_id)->first();
                if ($bebas) {
                    $rows->push(['student' => $student, 'bebas' => $bebas]);
                }
            }
        }

        return $this->render('payment.view_bebas', compact('payment','classes','majorsList','rows'));
    }

    public function addBebasForm(Request $request, $payment_id, $mode) {
        $this->requireSuperuser();
        $payment = Payment::with(['pos','period'])->findOrFail($payment_id);
        $classes = StudentClass::orderBy('class_name')->get();
        $majorsList = Majors::orderBy('majors_name')->get();
        $students = Student::active()->orderBy('student_full_name')->get();

        return $this->render('payment.tarif_bebas_form', compact(
            'payment','classes','majorsList','students','mode'
        ));
    }

    public function storeBebas(Request $request, $payment_id, $mode) {
        $this->requireSuperuser();
        $request->validate([
            'bebas_bill' => 'required|numeric',
            'bebas_desc' => 'nullable',
        ]);

        $query = Student::active();
        if ($mode === 'class') {
            $request->validate(['class_id' => 'required']);
            $query->where('class_class_id', $request->class_id);
        } elseif ($mode === 'majors') {
            $request->validate(['class_id' => 'required', 'majors_id' => 'required']);
            $query->where('class_class_id', $request->class_id)
                  ->where('majors_majors_id', $request->majors_id);
        } else {
            $request->validate(['student_id' => 'required']);
            $query->where('student_id', $request->student_id);
        }
        $students = $query->get();

        if ($students->isEmpty()) {
            return back()->with('failed', 'Tidak ada siswa yang sesuai kriteria');
        }

        $existing = Bebas::where('payment_payment_id', $payment_id)
            ->whereIn('student_student_id', $students->pluck('student_id'))
            ->exists();

        if ($existing) {
            return redirect()->route('payment.viewBebas', $payment_id)
                ->with('failed', 'Duplikat Data — sebagian siswa sudah memiliki tagihan untuk pembayaran ini');
        }

        foreach ($students as $student) {
            Bebas::create([
                'student_student_id' => $student->student_id,
                'payment_payment_id' => $payment_id,
                'bebas_bill'         => $request->bebas_bill,
                'bebas_total_pay'    => 0,
                'bebas_desc'         => $request->bebas_desc,
                'bebas_input_date'   => now(),
                'bebas_last_update'  => now(),
            ]);
        }

        $this->writeLog('ADD', 'payment', 'Setting tarif bebas untuk ' . $students->count() . ' siswa');
        return redirect()->route('payment.viewBebas', $payment_id)->with('success', 'Setting Tarif berhasil');
    }

    public function editBebas($payment_id, $student_id, $bebas_id) {
        $this->requireSuperuser();
        $payment = Payment::with(['pos','period'])->findOrFail($payment_id);
        $student = Student::with(['class','majors'])->findOrFail($student_id);
        $bebas   = Bebas::findOrFail($bebas_id);

        return $this->render('payment.edit_bebas', compact('payment','student','bebas'));
    }

    public function updateBebas(Request $request, $payment_id, $student_id, $bebas_id) {
        $this->requireSuperuser();
        $request->validate(['bebas_bill' => 'required|numeric']);

        Bebas::findOrFail($bebas_id)->update([
            'bebas_bill'        => $request->bebas_bill,
            'bebas_desc'        => $request->bebas_desc,
            'bebas_last_update' => now(),
        ]);

        $this->writeLog('UPDATE', 'payment', 'Update tarif bebas siswa ID ' . $student_id);
        return redirect()->route('payment.viewBebas', $payment_id)->with('success', 'Update Tagihan berhasil');
    }
}
