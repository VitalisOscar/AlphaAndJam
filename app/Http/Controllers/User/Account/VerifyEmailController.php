<?php

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMail;
use App\Services\MailService;
use App\Services\TokenVerificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyEmailController extends Controller
{
    function sendVerificationEmail(Request $request, TokenVerificationService $tokenVerificationService, MailService $mail){
        $user = auth()->user();

        // Create a token
        $token = $tokenVerificationService->createToken($user, config('auth.email_link_expiry'), 'email');

        if(!$token){
            return $request->expectsJson()?
                $this->json->error('Sorry. Something went wrong'):
                redirect()->back()->with(['status_email' => false]);
        }

        // send the email
        $mail->send(new EmailVerificationMail($user, $token->token));

        return $request->expectsJson()?
            $this->json->success('Email sent. Check your inbox shortly'):
            redirect()->back()->with(['status_email' => true]);
    }

    /**
     * @param string $token Token from link
     */
    function verifyEmail(TokenVerificationService $tokenVerificationService, $token){
        $token = $tokenVerificationService->retrieveToken($token);

        if($token == null || $token->isExpired()){
            return response()->view('web.user._invalid_email_link');
        }

        // Retrieve the associated user
        $user = $token->user;

        if($user == null){
            return response()->view('web.user._invalid_email_link');
        }

        // verify email
        $verification = $user->verification;
        $verification['email'] = Carbon::now()->toString();

        $user->verification = $verification;

        // verify and delete all email tokens
        DB::beginTransaction();
        if($user->save() && $user->verification_tokens()->where('purpose', 'email')->delete()){
            DB::commit();
            return response()->view('web.user.verify_email', ['status' => true]);
        }

        DB::rollback();
        return response()->view('web.user.verify_email', ['status' => false]);
    }

}
