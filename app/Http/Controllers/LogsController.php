<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogsController extends Controller {
    public function index() {
        $logs = Log::with('user')->orderByDesc('log_id')->paginate(30);
        return $this->render('logs.index', compact('logs'));
    }
}
