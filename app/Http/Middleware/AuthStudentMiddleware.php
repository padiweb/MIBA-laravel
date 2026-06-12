<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthStudentMiddleware {
    public function handle(Request $request, Closure $next) {
        if (!session('student_id')) {
            return redirect()->route('portal.login')
                ->with('redirect', $request->fullUrl());
        }
        return $next($request);
    }
}
