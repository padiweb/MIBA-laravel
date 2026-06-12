<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Setting;
use App\Models\Log;

abstract class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Role constants - sama seperti constants.php di CI3
    const SUPERUSER = 1;
    const USER      = 2;
    const BENDAHARA = 3;

    // Data global yang tersedia di semua controller
    protected function globalData(): array {
        return [
            'app_name'    => Setting::getValue(1),
            'app_logo'    => Setting::getValue(6),
            'app_address' => Setting::getValue(2),
            'app_level'   => Setting::getValue(7), // 'senior' / dll - untuk menu Unit Pendidikan
            'user_id'     => session('user_id'),
            'user_name'   => session('user_fullname'),
            'user_role'   => session('user_rolename'),
            'user_role_id'=> session('user_role_id'),
            'user_image'  => session('user_image'),
        ];
    }

    // Helper: tulis log aktivitas
    protected function writeLog(string $action, string $module, string $info = '') {
        Log::create([
            'log_date'   => now(),
            'log_action' => $action,
            'log_module' => $module,
            'log_info'   => $info,
            'user_id'    => session('user_id'),
        ]);
    }

    // Helper: cek akses SUPERUSER, redirect jika bukan
    protected function requireSuperuser() {
        if (session('user_role_id') != self::SUPERUSER) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    // Helper: view dengan data global
    protected function render(string $view, array $data = []) {
        return view($view, array_merge($this->globalData(), $data));
    }
}
