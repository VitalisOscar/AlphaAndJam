<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifiedEmail
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
        // $user = auth()->user();

        // if(!isset($user->verification['email'])
        //     || !isset($user->verification['phone'])
        //     || !isset($user->verification['business'])){

        //     $json = \resolve(\App\Helpers\CustomJsonResponse::class);

        //     return $request->expectsJson() ?
        //         $json->error('Your business has not been verified completely. You need to verify your phone and email. We cannot therefore continue processing your request'):
        //         \response()->view('web.user.unverified');
        // }

        return $next($request);
    }
}
