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
        $this->writeLog('ADD', 'debit', 'Tambah pemasukan: '.$request->debit_desc);
        return redirect()->route('debit.index')->with('success', 'Data pemasukan ditambahkan');
    }

    // Tambah banyak baris sekaligus (add_glob di CI3)
    public function storeGlob(Request $request) {
        $request->validate([
            'debit_date'    => 'required|date',
            'debit_desc'    => 'required|array',
            'debit_desc.*'  => 'required',
            'debit_value'   => 'required|array',
            'debit_value.*' => 'required|numeric',
        ]);
        $descs  = $request->debit_desc;
        $values = $request->debit_value;
        foreach ($descs as $i => $desc) {
            Debit::create([
                'debit_date'       => $request->debit_date,
                'debit_desc'       => $desc,
                'debit_value'      => $values[$i],
                'user_user_id'     => session('user_id'),
                'debit_input_date' => now(),
                'debit_last_update'=> now(),
            ]);
        }
        $this->writeLog('ADD', 'debit', 'Tambah '.count($descs).' pemasukan sekaligus');
        return redirect()->route('debit.index')->with('success', 'Data pemasukan ditambahkan');
    }

    public function edit($id) {
        $debit = Debit::findOrFail($id);
        return $this->render('debit.edit', compact('debit'));
    }

    public function update(Request $request, $id) {
        $request->validate(['debit_date'=>'required','debit_desc'=>'required','debit_value'=>'required|numeric']);
        Debit::findOrFail($id)->update(array_merge(
            $request->except(['_token','_method']),
            ['user_user_id' => session('user_id'), 'debit_last_update' => now()]
        ));
        return redirect()->route('debit.index')->with('success', 'Data pemasukan diupdate');
    }

    public function destroy($id) {
        Debit::findOrFail($id)->delete();
        $this->writeLog('DELETE', 'debit', 'Hapus pemasukan ID:'.$id);
        return redirect()->route('debit.index')->with('success', 'Data pemasukan dihapus');
    }
}
