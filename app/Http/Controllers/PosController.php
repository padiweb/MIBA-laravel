<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pos;

class PosController extends Controller {

    public function index(Request $request) {
        $query = Pos::query();
        if ($request->filled('n'))
            $query->where('pos_name', 'like', '%'.$request->n.'%');
        $poses = $query->orderByDesc('pos_id')->paginate(20)->withQueryString();
        return $this->render('pos.index', compact('poses'));
    }

    public function store(Request $request) {
        $request->validate(['pos_name' => 'required']);
        Pos::create($request->except('_token'));
        return redirect()->route('pos.index')->with('success', 'Jenis biaya ditambahkan');
    }

    public function update(Request $request, $id) {
        Pos::findOrFail($id)->update($request->except(['_token','_method']));
        return redirect()->route('pos.index')->with('success', 'Jenis biaya diupdate');
    }

    public function destroy($id) {
        Pos::findOrFail($id)->delete();
        return redirect()->route('pos.index')->with('success', 'Jenis biaya dihapus');
    }
}
