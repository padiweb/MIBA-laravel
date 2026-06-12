<?php
namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Setting;

class AuthController extends PortalController {

    public function login() {
        if (session('student_id')) return redirect()->route('portal.dashboard');

        $setting = [
            'school' => Setting::getValue(1),
            'logo'   => Setting::getValue(6),
        ];
        return view('portal.login', compact('setting'));
    }

    public function doLogin(Request $request) {
        $request->validate([
            'nis'      => 'required',
            'password' => 'required',
        ], [
            'nis.required'      => 'NIS wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $student = Student::with(['class','majors'])
            ->where('student_nis', $request->nis)
            ->first();

        $valid = false;
        if ($student) {
            $stored = $student->student_password;
            if (str_starts_with($stored, '$2y$') || str_starts_with($stored, '$2a$')) {
                $valid = Hash::check($request->password, $stored);
            } else {
                $valid = (sha1($request->password) === $stored);
            }
        }

        if ($valid) {
            session([
                'student_id'        => $student->student_id,
                'student_nis'       => $student->student_nis,
                'student_fullname'  => $student->student_full_name,
                'student_img'       => $student->student_img,
            ]);

            $redirect = $request->input('redirect', route('portal.dashboard'));
            return redirect($redirect);
        }

        return back()
            ->with('failed', 'Maaf, NIS dan password tidak cocok!')
            ->withInput($request->only('nis'));
    }

    public function logout(Request $request) {
        $request->session()->forget(['student_id','student_nis','student_fullname','student_img']);
        $redirect = $request->input('redirect', route('portal.login'));
        return redirect($redirect);
    }
}
