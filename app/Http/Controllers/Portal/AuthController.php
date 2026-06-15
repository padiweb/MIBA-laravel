<?php
namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\Setting;

class AuthController extends PortalController {

    public function login() {
        if (session('student_id')) {
            return redirect()->route('portal.dashboard');
        }

        $setting = [
            'school' => Setting::getValue(1),
            'logo'   => Setting::getValue(6),
        ];
        return view('portal.login', compact('setting'));
    }

    public function doLogin(Request $request) {
        // Validasi manual (hindari redirect conflict dari ValidatesRequests trait)
        $validator = Validator::make($request->all(), [
            'nis'      => 'required',
            'password' => 'required',
        ], [
            'nis.required'      => 'NIS wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->route('portal.login')
                ->withErrors($validator)
                ->withInput($request->only('nis'));
        }

        $student = Student::with(['class', 'majors'])
            ->where('student_nis', trim($request->nis))
            ->first();

        $valid = false;
        if ($student) {
            $stored = $student->student_password ?? '';
            if (str_starts_with($stored, '$2y$') || str_starts_with($stored, '$2a$')) {
                $valid = Hash::check($request->password, $stored);
            } else {
                $valid = (sha1($request->password) === $stored);
            }
        }

        if ($valid) {
            $request->session()->put('student_id',       $student->student_id);
            $request->session()->put('student_nis',      $student->student_nis);
            $request->session()->put('student_fullname', $student->student_full_name);
            $request->session()->put('student_img',      $student->student_img);

            return redirect()->route('portal.dashboard');
        }

        return redirect()->route('portal.login')
            ->with('failed', 'NIS atau password tidak cocok!')
            ->withInput($request->only('nis'));
    }

    public function logout(Request $request) {
        $request->session()->forget([
            'student_id',
            'student_nis',
            'student_fullname',
            'student_img',
        ]);
        return redirect()->route('portal.login');
    }
}
