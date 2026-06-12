<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentClass;

class ClassController extends Controller {
    public function index(Request $request) {
        $this->requireSuperuser();
        $query = StudentClass::query();
        if ($request->filled('n'))
            $query->where('class_name', 'like', '%'.$request->n.'%');
        $classes = $query->orderBy('class_id')->paginate(10)->withQueryString();
        return $this->render('class.index', compact('classes'));
    }

    public function store(Request $request) {
        $this->requireSuperuser();
        $request->validate(['class_name' => 'required']);
        StudentClass::create($request->only('class_name'));
        return redirect()->route('class.index')->with('success', 'Tambah Keterangan Kelas berhasil');
    }

    public function update(Request $request, $id) {
        $this->requireSuperuser();
        $request->validate(['class_name' => 'required']);
        StudentClass::findOrFail($id)->update($request->only('class_name'));
        return redirect()->route('class.index')->with('success', 'Sunting Keterangan Kelas berhasil');
    }

    public function destroy(Request $request, $id) {
        $this->requireSuperuser();
        $class = StudentClass::findOrFail($id);

        // Cek apakah masih ada siswa di kelas ini (seperti CI3)
        if ($class->students()->count() > 0) {
            return redirect()->route('class.index')->with('failed', 'Data Kelas tidak dapat dihapus');
        }

        $class->delete();
        $this->writeLog('DELETE', 'class', 'Hapus kelas: ' . $class->class_name);
        return redirect()->route('class.index')->with('success', 'Hapus Kelas berhasil');
    }
}
