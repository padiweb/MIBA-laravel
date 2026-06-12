<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Month;

class MonthController extends Controller {
    public function index(Request $request) {
        $this->requireSuperuser();
        $query = Month::query();
        if ($request->filled('n'))
            $query->where('month_name', 'like', '%'.$request->n.'%');
        $months = $query->orderBy('month_id')->paginate(10)->withQueryString();
        return $this->render('month.index', compact('months'));
    }

    public function store(Request $request) {
        $this->requireSuperuser();
        $request->validate(['month_name' => 'required']);
        Month::create($request->only('month_name'));
        return redirect()->route('month.index')->with('success', 'Tambah Bulan berhasil');
    }

    public function update(Request $request, $id) {
        $this->requireSuperuser();
        $request->validate(['month_name' => 'required']);
        Month::findOrFail($id)->update($request->only('month_name'));
        return redirect()->route('month.index')->with('success', 'Sunting Bulan berhasil');
    }

    public function destroy($id) {
        $this->requireSuperuser();
        Month::findOrFail($id)->delete();
        return redirect()->route('month.index')->with('success', 'Hapus Bulan berhasil');
    }
}
