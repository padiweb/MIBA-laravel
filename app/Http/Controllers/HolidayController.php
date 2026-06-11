<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;

class HolidayController extends Controller {
    public function index() {
        $holidays = Holiday::orderByDesc('date')->get();
        return $this->render('holiday.index', compact('holidays'));
    }
    public function store(Request $request) {
        $request->validate(['date'=>'required|date','info'=>'required']);
        Holiday::create(['date'=>$request->date,'info'=>$request->info,'year'=>date('Y',strtotime($request->date))]);
        return redirect()->route('holiday.index')->with('success', 'Hari libur ditambahkan');
    }
    public function destroy($id) {
        Holiday::findOrFail($id)->delete();
        return redirect()->route('holiday.index')->with('success', 'Hari libur dihapus');
    }
}
