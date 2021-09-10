<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $staff = Auth::guard('staff')->user();
        if($staff->isAdmin()){
            return $next($request);
        }

        // If staff is not an administrator, show error
        return view('admin._not_admin');
    }
}
