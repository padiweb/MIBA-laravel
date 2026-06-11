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

    // Data global yang tersedia di semua controller
    protected function globalData(): array {
        return [
            'app_name'    => Setting::getValue(1),
            'app_logo'    => Setting::getValue(6),
            'app_address' => Setting::getValue(2),
            'user_id'     => session('user_id'),
            'user_name'   => session('user_fullname'),
            'user_role'   => session('user_rolename'),
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

    // Helper: view dengan data global
    protected function render(string $view, array $data = []) {
        return view($view, array_merge($this->globalData(), $data));
    }
}
