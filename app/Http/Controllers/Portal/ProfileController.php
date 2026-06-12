<?php
namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentClass;

class ProfileController extends PortalController {

    public function index() {
        $student = Student::with(['class','majors'])->findOrFail(session('student_id'));
        return $this->render('portal.profile.index', compact('student'));
    }

    public function edit() {
        $student = Student::with(['class','majors'])->findOrFail(session('student_id'));
        return $this->render('portal.profile.edit', compact('student'));
    }

    public function update(Request $request) {
        $student = Student::findOrFail(session('student_id'));

        $student->update([
            'student_gender'         => $request->student_gender,
            'student_born_place'     => $request->student_born_place,
            'student_born_date'      => $request->student_born_date,
            'student_hobby'          => $request->student_hobby,
            'student_phone'          => $request->student_phone,
            'student_address'        => $request->student_address,
            'student_name_of_mother' => $request->student_name_of_mother,
            'student_name_of_father' => $request->student_name_of_father,
            'student_parent_phone'   => $request->student_parent_phone,
            'student_last_update'    => now(),
        ]);

        session(['student_fullname' => $student->student_full_name, 'student_img' => $student->student_img]);

        return redirect()->route('portal.profile')->with('success', 'Edit Profil Siswa Berhasil');
    }

    public function changePasswordForm() {
        return $this->render('portal.profile.change_password');
    }

    public function changePassword(Request $request) {
        $request->validate([
            'student_current_password' => 'required',
            'student_password'         => 'required|min:6|same:passconf',
            'passconf'                  => 'required|min:6',
        ], [
            'student_password.same' => 'Konfirmasi password baru tidak cocok',
        ]);

        $student = Student::findOrFail(session('student_id'));

        if (sha1($request->student_current_password) !== $student->student_password) {
            return back()->withErrors(['student_current_password' => 'Password lama tidak sesuai']);
        }

        $student->update(['student_password' => sha1($request->student_password)]);

        return redirect()->route('portal.profile')->with('success', 'Ubah password Siswa berhasil');
    }
}
