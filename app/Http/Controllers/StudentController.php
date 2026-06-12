<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Majors;
use App\Models\Setting;
use App\Helpers\Barcode;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $file->move(public_path('uploads/student'), $filename);
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
            $file->move(public_path('uploads/student'), $filename);
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

    // ===== Import Siswa (paste dari Excel) =====
    public function importForm() {
        return $this->render('student.import');
    }

    public function importStore(Request $request) {
        $appLevel = $this->globalData()['app_level'] ?? '';
        $rows = explode("\n", str_replace("\r", "", $request->input('rows', '')));

        $success = 0; $failed = 0; $exist = 0;
        $expectedCols = $appLevel == 'senior' ? 14 : 13;

        foreach ($rows as $row) {
            $row = trim($row);
            if ($row === '') continue;
            $exp = explode("\t", $row);
            if (count($exp) != $expectedCols) { $failed++; continue; }

            $nis = trim($exp[0]);
            $bornDate = trim($exp[5]);

            $classId = trim($exp[12]);
            $class = StudentClass::find($classId);

            if (Student::where('student_nis', $nis)->exists()) {
                $exist++;
                continue;
            }
            if (!$classId || !$class) {
                return back()->with('failed', 'ID Kelas tidak ada (baris dengan NIS ' . $nis . ')');
            }

            Student::create([
                'student_nis'            => $nis,
                'student_nisn'           => trim($exp[1]),
                'student_password'       => sha1(date('dmY', strtotime(str_replace('-','',$bornDate)))),
                'student_full_name'      => trim($exp[2]),
                'student_gender'         => trim($exp[3]),
                'student_born_place'     => trim($exp[4]),
                'student_born_date'      => $bornDate,
                'student_hobby'          => trim($exp[6]),
                'student_phone'          => trim($exp[7]),
                'student_address'        => trim($exp[8]),
                'student_name_of_mother' => trim($exp[9]),
                'student_name_of_father' => trim($exp[10]),
                'student_parent_phone'   => trim($exp[11]),
                'class_class_id'         => $classId,
                'majors_majors_id'       => $appLevel == 'senior' ? trim($exp[13]) : null,
                'student_status'         => 1,
                'student_input_date'     => now(),
                'student_last_update'    => now(),
            ]);
            $success++;
        }

        $msg = "Sukses : $success baris, Gagal : $failed, Duplikat : $exist";
        $this->writeLog('ADD', 'student', 'Import siswa: ' . $msg);
        return redirect()->route('student.importForm')->with('success', $msg);
    }

    // Download template excel data siswa
    public function downloadTemplate() {
        $appLevel = $this->globalData()['app_level'] ?? '';
        $file = $appLevel == 'senior' ? 'Template_Data_Siswa_Senior.xls' : 'Template_Data_Siswa_Primary.xls';
        $path = public_path('media/template_excel/' . $file);
        if (!file_exists($path)) {
            $path = public_path('media/template_excel/Template_Data_Siswa.xls');
        }
        return response()->download($path);
    }

    // ===== Kartu Siswa (Cetak ID Card dengan Barcode) =====

    private function cardSettings(): array {
        return [
            'school'   => Setting::getValue(1),
            'address'  => Setting::getValue(2),
            'phone'    => Setting::getValue(3),
            'district' => Setting::getValue(4),
            'city'     => Setting::getValue(5),
        ];
    }

    // Cetak kartu satu siswa
    public function printPdf($id) {
        $student = Student::with(['class','majors'])->findOrFail($id);
        $setting = $this->cardSettings();
        $students = collect([$student]);

        $pdf = Pdf::loadView('student.kartu', compact('students', 'setting'))
                  ->setPaper('a4', 'portrait');
        return $pdf->stream('kartu-' . $student->student_nis . '.pdf');
    }

    // Cetak kartu banyak siswa sekaligus (dari checkbox)
    public function printCards(Request $request) {
        $ids = $request->input('msg', []);
        $students = Student::with(['class','majors'])->whereIn('student_id', $ids)->get();
        $setting = $this->cardSettings();

        $pdf = Pdf::loadView('student.kartu', compact('students', 'setting'))
                  ->setPaper('a4', 'portrait');
        return $pdf->stream('kartu-siswa.pdf');
    }

    // ===== Reset Password Siswa =====
    public function resetPasswordForm($id) {
        $student = Student::findOrFail($id);
        return $this->render('student.reset_password', compact('student'));
    }

    public function resetPassword(Request $request, $id) {
        $request->validate([
            'student_password' => 'required|min:6',
            'passconf'          => 'required|same:student_password',
        ], [
            'passconf.same' => 'Password dan konfirmasi password tidak cocok',
        ]);

        $student = Student::findOrFail($id);
        $student->update(['student_password' => sha1($request->student_password)]);

        $this->writeLog('UPDATE', 'student', 'Reset password siswa: ' . $student->student_full_name);

        return redirect()->route('student.index')->with('success', 'Reset Password Berhasil');
    }

    // ===== Kenaikan Kelas =====
    public function upgrade(Request $request) {
        $query = Student::with(['class','majors'])->active();
        if ($request->filled('pr')) $query->where('class_class_id', $request->pr);
        $students = $query->orderBy('class_class_id')->orderBy('student_full_name')->paginate(30)->withQueryString();
        $classes  = StudentClass::orderBy('class_name')->get();
        return $this->render('student.upgrade', compact('students', 'classes'));
    }

    // ===== Kelulusan =====
    public function pass(Request $request) {
        $queryActive = Student::with(['class','majors'])->active();
        if ($request->filled('pr')) $queryActive->where('class_class_id', $request->pr);
        $notpass = $queryActive->orderBy('class_class_id')->orderBy('student_full_name')->paginate(30, ['*'], 'notpass_page')->withQueryString();

        $pass = Student::with(['class','majors'])->where('student_status', 0)
            ->orderBy('student_full_name')->paginate(30, ['*'], 'pass_page')->withQueryString();

        $classes = StudentClass::orderBy('class_name')->get();
        return $this->render('student.pass', compact('notpass', 'pass', 'classes'));
    }

    // ===== Aksi massal: kelulusan, kembali aktif, kenaikan kelas =====
    public function multiple(Request $request) {
        $action = $request->input('action');
        $ids    = $request->input('msg', []);

        if ($action == 'pass') {
            foreach ($ids as $id) {
                Student::where('student_id', $id)->update([
                    'student_status' => 0,
                    'student_last_update' => now(),
                ]);
            }
            $this->writeLog('UPDATE', 'student', 'Proses Lulus: ' . count($ids) . ' siswa');
            return redirect()->route('student.pass')->with('success', 'Proses Lulus berhasil');

        } elseif ($action == 'notpass') {
            foreach ($ids as $id) {
                Student::where('student_id', $id)->update([
                    'student_status' => 1,
                    'student_last_update' => now(),
                ]);
            }
            $this->writeLog('UPDATE', 'student', 'Proses Kembali Aktif: ' . count($ids) . ' siswa');
            return redirect()->route('student.pass')->with('success', 'Proses Kembali berhasil');

        } elseif ($action == 'upgrade') {
            $classId = $request->input('class_id');
            foreach ($ids as $id) {
                Student::where('student_id', $id)->update([
                    'class_class_id' => $classId,
                    'student_last_update' => now(),
                ]);
            }
            $this->writeLog('UPDATE', 'student', 'Proses Kenaikan Kelas: ' . count($ids) . ' siswa');
            return redirect()->route('student.upgrade')->with('success', 'Proses Kenaikan Kelas berhasil');
        }

        return redirect()->route('student.index');
    }
}
