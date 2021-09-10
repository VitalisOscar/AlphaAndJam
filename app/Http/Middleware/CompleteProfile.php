<?php

namespace App\Http\Middleware;

use App\Helpers\CustomJsonResponse;
use Closure;
use Illuminate\Http\Request;

class CompleteProfile
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
        $user = $request->user();

        if($user->profileComplete()){
            return $next($request);
        }

        /** @var CustomJsonResponse */
        $json = resolve(CustomJsonResponse::class);

        return $request->expectsJson() ?
            $json->error('Your account profile is incomplete. Complete then try again')
            :response()->view('web.auth.signup_end');
    }
}
