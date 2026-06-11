<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller {

    public function index() {
        $settings = Setting::orderBy('setting_id')->get()->keyBy('setting_id');
        return $this->render('setting.index', compact('settings'));
    }

    public function update(Request $request) {
        $map = [
            1 => 'school', 2 => 'address', 3 => 'phone',
            4 => 'district', 5 => 'city', 7 => 'level',
        ];
        foreach ($map as $id => $key) {
            if ($request->has($key)) {
                Setting::where('setting_id', $id)
                    ->update(['setting_value' => $request->$key, 'setting_last_update' => now()]);
            }
        }
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            Setting::where('setting_id', 6)
                ->update(['setting_value' => $filename, 'setting_last_update' => now()]);
        }
        return redirect()->route('setting.index')->with('success', 'Pengaturan disimpan');
    }
}
