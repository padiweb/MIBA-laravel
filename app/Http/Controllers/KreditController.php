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
        return redirect()->route('kredit.index')->with('success', 'Data kredit ditambahkan');
    }
    public function destroy($id) {
        Kredit::findOrFail($id)->delete();
        return redirect()->route('kredit.index')->with('success', 'Data kredit dihapus');
    }
}
