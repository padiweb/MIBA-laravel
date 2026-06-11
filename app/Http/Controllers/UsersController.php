<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserRole;

class UsersController extends Controller {

    public function index(Request $request) {
        $query = User::with('role')->active();
        if ($request->filled('n'))
            $query->where(function($q) use ($request) {
                $q->where('user_full_name', 'like', '%'.$request->n.'%')
                  ->orWhere('user_email', 'like', '%'.$request->n.'%');
            });
        $users = $query->orderByDesc('user_id')->paginate(20)->withQueryString();
        $roles = UserRole::all();
        return $this->render('users.index', compact('users', 'roles'));
    }

    public function store(Request $request) {
        $request->validate([
            'user_email'    => 'required|email|unique:users,user_email',
            'user_password' => 'required|min:6',
            'user_full_name'=> 'required',
        ]);
        User::create([
            'user_email'       => $request->user_email,
            'user_password'    => Hash::make($request->user_password),
            'user_full_name'   => $request->user_full_name,
            'user_role_role_id'=> $request->user_role_role_id,
            'user_description' => $request->user_description,
            'user_input_date'  => now(),
            'user_last_update' => now(),
        ]);
        return redirect()->route('users.index')->with('success', 'Pengguna ditambahkan');
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $data = $request->except(['_token','_method','user_password']);
        if ($request->filled('user_password'))
            $data['user_password'] = Hash::make($request->user_password);
        $data['user_last_update'] = now();
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'Pengguna diupdate');
    }

    public function destroy($id) {
        User::findOrFail($id)->update(['user_is_deleted' => 1]);
        return redirect()->route('users.index')->with('success', 'Pengguna dihapus');
    }

    // Role management
    public function roles() {
        $roles = UserRole::all();
        return $this->render('users.roles', compact('roles'));
    }

    public function storeRole(Request $request) {
        $request->validate(['role_name' => 'required']);
        UserRole::create($request->only('role_name'));
        return redirect()->route('users.roles')->with('success', 'Role ditambahkan');
    }

    public function updateRole(Request $request, $id) {
        UserRole::findOrFail($id)->update($request->only('role_name'));
        return redirect()->route('users.roles')->with('success', 'Role diupdate');
    }

    public function destroyRole($id) {
        UserRole::findOrFail($id)->delete();
        return redirect()->route('users.roles')->with('success', 'Role dihapus');
    }
}
