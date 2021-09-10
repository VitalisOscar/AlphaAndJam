<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    function __invoke(Request $request, $type = 'client'){
        if(\validator()->make($request->post(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ])->fails()){
            return \back()->withInput()
            ->withErrors(['status' => 'Please provide a valid email and password']);
        }

        if($type == 'agent'){
            $user = User::agent()
                ->where('user->email', $request->post('email'))
                ->approved()
                ->first();

            if($user == null){
                return \back()->withInput()
                ->withErrors(['status' => 'Agent account was not found or is deactivated']);
            }

            if(Auth::guard('web')->attempt(['user->email' => $request->post('email'), 'password' => $request->post('password')], $request->boolean('remember_me'))){
                return \redirect()->intended(route('web.user.dashboard'));
            }
        }else{
            // Client login
            if(Auth::guard('web')->attempt(['business->email' => $request->post('email'), 'password' => $request->post('password')], $request->boolean('remember_me'))){
                return \redirect()->intended(route('web.user.dashboard'));
            }
        }

        return \back()->withInput()
            ->withErrors(['status' => 'Unable to authenticate you. Please check the credentials and retry']);
    }

    function appLogin(Request $request){
        if(\validator()->make($request->post(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ])->fails()){
            return $this->json->error('Please provide a valid email and password');
        }

        $user = new User();

        // Find user
        $user = User::where([
            'business->email' => $request->post('email'),
            'password' => Hash::make($request->post('password'))
        ]);

        if($user != null){
            // Logo
            if($user->logo != null) $user->logo = asset(User::LOGO_DIR.'/'.$user->logo);

            // Create access token


            return $this->json->data($user);
        }

        return $this->json->error('Unable to authenticate you. Please check the credentials and retry');
    }
}
