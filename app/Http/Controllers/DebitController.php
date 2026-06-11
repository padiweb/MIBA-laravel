<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debit;

class DebitController extends Controller {
    public function index(Request $request) {
        $debits = Debit::with('user')->orderByDesc('debit_date')->paginate(20);
        return $this->render('debit.index', compact('debits'));
    }
    public function store(Request $request) {
        $request->validate(['debit_date'=>'required','debit_desc'=>'required','debit_value'=>'required|numeric']);
        Debit::create(array_merge($request->except('_token'), [
            'user_user_id' => session('user_id'),
            'debit_input_date' => now(), 'debit_last_update' => now(),
        ]));
        return redirect()->route('debit.index')->with('success', 'Data debit ditambahkan');
    }
    public function destroy($id) {
        Debit::findOrFail($id)->delete();
        return redirect()->route('debit.index')->with('success', 'Data debit dihapus');
    }
}
