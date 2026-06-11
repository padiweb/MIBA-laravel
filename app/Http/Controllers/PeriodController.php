<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;

class PeriodController extends Controller {

    public function index() {
        $periods = Period::orderByDesc('period_id')->get();
        return $this->render('period.index', compact('periods'));
    }

    public function store(Request $request) {
        $request->validate([
            'period_start' => 'required',
            'period_end'   => 'required',
        ]);
        Period::create($request->except('_token'));
        return redirect()->route('period.index')->with('success', 'Tahun pelajaran ditambahkan');
    }

    public function update(Request $request, $id) {
        Period::findOrFail($id)->update($request->except(['_token','_method']));
        return redirect()->route('period.index')->with('success', 'Tahun pelajaran diupdate');
    }

    public function setActive($id) {
        Period::query()->update(['period_status' => 0]);
        Period::findOrFail($id)->update(['period_status' => 1]);
        return redirect()->route('period.index')->with('success', 'Tahun pelajaran aktif diubah');
    }

    public function destroy($id) {
        Period::findOrFail($id)->delete();
        return redirect()->route('period.index')->with('success', 'Tahun pelajaran dihapus');
    }
}
