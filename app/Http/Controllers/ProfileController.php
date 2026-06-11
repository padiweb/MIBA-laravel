<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller {
    public function index() {
        $user = User::findOrFail(session('user_id'));
        return $this->render('profile.index', compact('user'));
    }
    public function update(Request $request) {
        $user = User::findOrFail(session('user_id'));
        $data = $request->except(['_token','user_image']);
        $data['user_last_update'] = now();
        if ($request->hasFile('user_image')) {
            $file = $request->file('user_image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/users'), $filename);
            $data['user_image'] = $filename;
            session(['user_image' => $filename]);
        }
        $user->update($data);
        session(['user_fullname' => $request->user_full_name]);
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diupdate');
    }
    public function changePassword(Request $request) {
        $request->validate([
            'old_password'     => 'required',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);
        $user = User::findOrFail(session('user_id'));
        $valid = str_starts_with($user->user_password, '$2y$')
            ? Hash::check($request->old_password, $user->user_password)
            : sha1($request->old_password) === $user->user_password;
        if (!$valid) return back()->with('failed', 'Password lama tidak sesuai');
        $user->update(['user_password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password berhasil diubah');
    }
}
