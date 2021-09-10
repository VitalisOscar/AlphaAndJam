<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConfirmPassword extends \Illuminate\Auth\Middleware\RequirePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        // if ($this->shouldConfirmPassword($request)) {
        //     if ($request->expectsJson()) {
        //         return $this->responseFactory->json([
        //             'message' => 'Password confirmation required.',
        //         ], 423);
        //     }

        //     return $this->responseFactory->redirectGuest(
        //         $this->urlGenerator->route($redirectToRoute ?? 'password.confirm')
        //     );
        // }

        return $next($request);
    }
}
