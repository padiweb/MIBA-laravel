<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Information;

class InformationController extends Controller {
    public function index() {
        $informations = Information::orderByDesc('information_id')->paginate(20);
        return $this->render('information.index', compact('informations'));
    }
    public function create() {
        return $this->render('information.form');
    }
    public function store(Request $request) {
        $request->validate(['information_title' => 'required']);
        $data = $request->except('_token');
        $data['user_user_id'] = session('user_id');
        $data['information_input_date'] = now();
        $data['information_last_update'] = now();
        if ($request->hasFile('information_img')) {
            $file = $request->file('information_img');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/information'), $filename);
            $data['information_img'] = $filename;
        }
        Information::create($data);
        return redirect()->route('information.index')->with('success', 'Informasi ditambahkan');
    }
    public function edit($id) {
        $information = Information::findOrFail($id);
        return $this->render('information.form', compact('information'));
    }
    public function update(Request $request, $id) {
        $info = Information::findOrFail($id);
        $data = $request->except(['_token','_method']);
        $data['information_last_update'] = now();
        if ($request->hasFile('information_img')) {
            $file = $request->file('information_img');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/information'), $filename);
            $data['information_img'] = $filename;
        }
        $info->update($data);
        return redirect()->route('information.index')->with('success', 'Informasi diupdate');
    }
    public function destroy($id) {
        Information::findOrFail($id)->delete();
        return redirect()->route('information.index')->with('success', 'Informasi dihapus');
    }
}
