<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Period;
use App\Models\Pos;

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
        $periods = Period::orderByDesc('period_id')->get();
        $poses   = Pos::orderBy('pos_name')->get();
        return $this->render('payment.form', compact('periods', 'poses'));
    }

    public function store(Request $request) {
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
        $payment = Payment::findOrFail($id);
        $periods = Period::orderByDesc('period_id')->get();
        $poses   = Pos::orderBy('pos_name')->get();
        return $this->render('payment.form', compact('payment', 'periods', 'poses'));
    }

    public function update(Request $request, $id) {
        Payment::findOrFail($id)->update(array_merge(
            $request->except(['_token','_method']),
            ['payment_last_update' => now()]
        ));
        return redirect()->route('payment.index')->with('success', 'Jenis pembayaran diupdate');
    }

    public function destroy($id) {
        Payment::findOrFail($id)->delete();
        return redirect()->route('payment.index')->with('success', 'Jenis pembayaran dihapus');
    }
}
