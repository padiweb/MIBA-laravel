<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Setting;

abstract class PortalController extends Controller {

    // Data global untuk layout portal siswa
    protected function globalData(): array {
        return [
            'app_name'     => Setting::getValue(1),
            'app_logo'     => Setting::getValue(6),
            'app_level'    => Setting::getValue(7),
            'student_id'   => session('student_id'),
            'student_nis'  => session('student_nis'),
            'student_name' => session('student_fullname'),
            'student_img'  => session('student_img'),
        ];
    }

    protected function render(string $view, array $data = []) {
        return view($view, array_merge($this->globalData(), $data));
    }
}
