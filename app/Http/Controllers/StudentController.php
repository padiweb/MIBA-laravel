<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Majors;

class StudentController extends Controller {

    public function index(Request $request) {
        $query = Student::with(['class', 'majors']);

        if ($request->filled('n')) {
            $q = $request->n;
            $query->where(function($q2) use ($q) {
                $q2->where('student_nis', 'like', "%$q%")
                   ->orWhere('student_full_name', 'like', "%$q%");
            });
        }
        if ($request->filled('class_id'))
            $query->where('class_class_id', $request->class_id);
        if ($request->filled('majors_id'))
            $query->where('majors_majors_id', $request->majors_id);
        if ($request->filled('status'))
            $query->where('student_status', $request->status);

        $students = $query->orderBy('student_full_name')->paginate(20)->withQueryString();
        $classes  = StudentClass::orderBy('class_name')->get();
        $majors   = Majors::orderBy('majors_name')->get();

        return $this->render('student.index', compact('students', 'classes', 'majors'));
    }

    public function create() {
        $classes = StudentClass::orderBy('class_name')->get();
        $majors  = Majors::orderBy('majors_name')->get();
        return $this->render('student.form', compact('classes', 'majors'));
    }

    public function store(Request $request) {
        $request->validate([
            'student_nis'       => 'required|unique:student,student_nis',
            'student_full_name' => 'required',
            'class_class_id'    => 'required',
            'majors_majors_id'  => 'required',
        ]);

        $data = $request->except(['_token']);
        $data['student_password']   = sha1($request->student_nis);
        $data['student_input_date'] = now();
        $data['student_last_update']= now();

        if ($request->hasFile('student_img')) {
            $file = $request->file('student_img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/students'), $filename);
            $data['student_img'] = $filename;
        }

        Student::create($data);
        $this->writeLog('ADD', 'student', 'Tambah siswa: ' . $request->student_full_name);

        return redirect()->route('student.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    public function edit($id) {
        $student = Student::findOrFail($id);
        $classes = StudentClass::orderBy('class_name')->get();
        $majors  = Majors::orderBy('majors_name')->get();
        return $this->render('student.form', compact('student', 'classes', 'majors'));
    }

    public function update(Request $request, $id) {
        $student = Student::findOrFail($id);
        $request->validate([
            'student_nis'       => 'required|unique:student,student_nis,' . $id . ',student_id',
            'student_full_name' => 'required',
        ]);

        $data = $request->except(['_token', '_method']);
        $data['student_last_update'] = now();

        if ($request->hasFile('student_img')) {
            $file = $request->file('student_img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/students'), $filename);
            $data['student_img'] = $filename;
        }

        $student->update($data);
        $this->writeLog('UPDATE', 'student', 'Update siswa: ' . $request->student_full_name);

        return redirect()->route('student.index')
            ->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy($id) {
        $student = Student::findOrFail($id);
        $name = $student->student_full_name;
        $student->delete();
        $this->writeLog('DELETE', 'student', 'Hapus siswa: ' . $name);
        return redirect()->route('student.index')
            ->with('success', 'Siswa berhasil dihapus');
    }

    public function show($id) {
        $student = Student::with(['class', 'majors'])->findOrFail($id);
        return $this->render('student.show', compact('student'));
    }

    // Class management
    public function classes(Request $request) {
        $classes = StudentClass::orderBy('class_name')->get();
        return $this->render('student.classes', compact('classes'));
    }

    public function storeClass(Request $request) {
        $request->validate(['class_name' => 'required|unique:class,class_name']);
        StudentClass::create($request->only('class_name'));
        return redirect()->route('student.classes')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    public function updateClass(Request $request, $id) {
        $class = StudentClass::findOrFail($id);
        $request->validate(['class_name' => 'required|unique:class,class_name,' . $id . ',class_id']);
        $class->update($request->only('class_name'));
        return redirect()->route('student.classes')
            ->with('success', 'Kelas berhasil diupdate');
    }

    public function destroyClass($id) {
        StudentClass::findOrFail($id)->delete();
        return redirect()->route('student.classes')
            ->with('success', 'Kelas berhasil dihapus');
    }

    // Majors management
    public function majorsList(Request $request) {
        $majors = Majors::orderBy('majors_name')->get();
        return $this->render('student.majors', compact('majors'));
    }

    public function storeMajors(Request $request) {
        $request->validate(['majors_name' => 'required']);
        Majors::create($request->only(['majors_name', 'majors_short_name']));
        return redirect()->route('student.majors')
            ->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function updateMajors(Request $request, $id) {
        Majors::findOrFail($id)->update($request->only(['majors_name', 'majors_short_name']));
        return redirect()->route('student.majors')
            ->with('success', 'Jurusan berhasil diupdate');
    }

    public function destroyMajors($id) {
        Majors::findOrFail($id)->delete();
        return redirect()->route('student.majors')
            ->with('success', 'Jurusan berhasil dihapus');
    }
}
