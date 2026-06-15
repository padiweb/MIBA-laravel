<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

class AuthController extends Controller {

    public function index() {
        // Halaman utama = login unified (siswa + admin)
        if (session('user_id'))      return redirect()->route('dashboard');
        if (session('student_id'))   return redirect()->route('portal.dashboard');
        $setting = [
            'school' => Setting::getValue(1),
            'logo'   => Setting::getValue(6),
        ];
        return view('auth.login-unified', compact('setting'));
    }

    public function login() {
        if (session('user_id')) return redirect()->route('dashboard');
        $setting = [
            'school' => Setting::getValue(1),
            'logo'   => Setting::getValue(6),
        ];
        return view('auth.login', compact('setting'));
    }

    public function doLogin(Request $request) {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $user = User::with('role')
            ->active()
            ->where('user_email', $request->email)
            ->first();

        // Cek password - support sha1 lama (CI3) dan bcrypt baru (Laravel)
        $valid = false;
        if ($user) {
            $storedPassword = $user->user_password;
            // Cek apakah password disimpan dalam format bcrypt ($2y$)
            if (str_starts_with($storedPassword, '$2y$') || str_starts_with($storedPassword, '$2a$')) {
                // Format bcrypt - pakai Hash::check
                try {
                    $valid = Hash::check($request->password, $storedPassword);
                } catch (\Exception $e) {
                    $valid = false;
                }
            } else {
                // Format SHA1 lama dari CI3
                $valid = (sha1($request->password) === $storedPassword);
            }
        }

        if ($valid) {
            session([
                'user_id'       => $user->user_id,
                'user_email'    => $user->user_email,
                'user_fullname' => $user->user_full_name,
                'user_role_id'  => $user->user_role_role_id,
                'user_rolename' => $user->role->role_name ?? '',
                'user_image'    => $user->user_image,
            ]);

            $redirect = $request->input('redirect', route('dashboard'));
            return redirect($redirect);
        }

        return back()
            ->with('failed', 'Email atau password tidak cocok!')
            ->withInput($request->only('email'));
    }

    public function logout(Request $request) {
        $redirect = $request->input('redirect', route('login'));
        $request->session()->flush();
        return redirect($redirect);
    }
}
