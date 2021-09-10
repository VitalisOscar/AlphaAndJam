<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Services\MailService;
use App\Services\TokenVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    function forgot(Request $request, TokenVerificationService $tokenVerificationService, MailService $mail){
        if(\validator()->make($request->post(), [
            'email' => ['required', 'email']
        ])->fails()){
            return $request->expectsJson() ?
                :
                \back()->withInput()->withErrors(['email' => 'Provide a valid email']);
        }

        $user = User::where('business->email', $request->post('email'))->first();

        if($user == null){
            return $request->expectsJson() ?
                :
                \back()->withInput()->withErrors(['email' => 'There is no account associated with this email']);
        }

        // Create a token
        $token = $tokenVerificationService->createToken($user, config('auth.passwords.users.expire'), 'password');

        if($token != null){
            // Send email
            $mail->send(new PasswordResetMail($user, $token->token));

            return $request->expectsJson() ?
                $this->json->success('A link to reset your password has been sent to your email'):
                \back()->withInput()->with(['status' => 'A link to reset your password has been sent to your email']);
        }

        return $request->expectsJson() ?
                $this->json->error('Oops. Something went wrong'):
                \back()->withInput()->withErrors(['status' => 'Oops. Something went wrong']);
    }

    function reset(Request $request, TokenVerificationService $tokenVerificationService, $token){
        $token = $tokenVerificationService->retrieveToken($token);

        // validate
        if($token == null || $token->isExpired()){
            return \response()
                ->view('web.auth._invalid_reset_link');
        }

        // Link validated, get user
        $user = $token->user;

        if($user == null){
            return \response()
                ->view('web.auth._invalid_reset_link');
        }

        // Allow user to submit new password
        if($request->isMethod('GET')){
            return \response()->view('web.auth.reset_password', ['user' => $user]);
        }

        // POST
        $validator = Validator::make($request->post(), [
            'new_password' => ['required', 'regex:/([a-zA-Z]){8,}/'],
            'confirm_password' => ['required', 'same:new_password'],
        ], [
            'new_password.regex' => 'Password should contain at least 8 characters with at least one letter',
            'confirm_password.same' => 'Passwords do not match!',
        ]);

        if($validator->fails()){
            return \back()->withInput()->withErrors($validator->errors());
        }

        $password = $request->post('new_password');

        DB::beginTransaction();

        $user->password = Hash::make($password);

        // Delete all resets for user account and save new password
        if($user->save() && $user->verification_tokens()->where('purpose', 'password')->delete()){
            DB::commit();
            return redirect()->route('web.auth.login')->with(['status' => 'Password has been reset. You can now login using the new password']);
        }

        DB::rollback();
        return \back()->withInput()->withErrors(['status' => 'Oops. Something went wrong']);

    }
}
