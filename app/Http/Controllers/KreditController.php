<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kredit;

class KreditController extends Controller {
    public function index(Request $request) {
        $kredits = Kredit::with('user')->orderByDesc('kredit_date')->paginate(20);
        return $this->render('kredit.index', compact('kredits'));
    }

    public function store(Request $request) {
        $request->validate(['kredit_date'=>'required','kredit_desc'=>'required','kredit_value'=>'required|numeric']);
        Kredit::create(array_merge($request->except('_token'), [
            'user_user_id' => session('user_id'),
            'kredit_input_date' => now(), 'kredit_last_update' => now(),
        ]));
        $this->writeLog('ADD', 'kredit', 'Tambah pengeluaran: '.$request->kredit_desc);
        return redirect()->route('kredit.index')->with('success', 'Data pengeluaran ditambahkan');
    }

    // Tambah banyak baris sekaligus (add_glob di CI3)
    public function storeGlob(Request $request) {
        $request->validate([
            'kredit_date'    => 'required|date',
            'kredit_desc'    => 'required|array',
            'kredit_desc.*'  => 'required',
            'kredit_value'   => 'required|array',
            'kredit_value.*' => 'required|numeric',
        ]);
        $descs  = $request->kredit_desc;
        $values = $request->kredit_value;
        foreach ($descs as $i => $desc) {
            Kredit::create([
                'kredit_date'       => $request->kredit_date,
                'kredit_desc'       => $desc,
                'kredit_value'      => $values[$i],
                'user_user_id'      => session('user_id'),
                'kredit_input_date' => now(),
                'kredit_last_update'=> now(),
            ]);
        }
        $this->writeLog('ADD', 'kredit', 'Tambah '.count($descs).' pengeluaran sekaligus');
        return redirect()->route('kredit.index')->with('success', 'Data pengeluaran ditambahkan');
    }

    public function edit($id) {
        $kredit = Kredit::findOrFail($id);
        return $this->render('kredit.edit', compact('kredit'));
    }

    public function update(Request $request, $id) {
        $request->validate(['kredit_date'=>'required','kredit_desc'=>'required','kredit_value'=>'required|numeric']);
        Kredit::findOrFail($id)->update(array_merge(
            $request->except(['_token','_method']),
            ['user_user_id' => session('user_id'), 'kredit_last_update' => now()]
        ));
        return redirect()->route('kredit.index')->with('success', 'Data pengeluaran diupdate');
    }

    public function destroy($id) {
        Kredit::findOrFail($id)->delete();
        $this->writeLog('DELETE', 'kredit', 'Hapus pengeluaran ID:'.$id);
        return redirect()->route('kredit.index')->with('success', 'Data pengeluaran dihapus');
    }
}
