<?php

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use App\Services\SendSMSMessageService;
use App\Services\TokenVerificationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyPhoneController extends Controller
{
    function sendCode(Request $request, TokenVerificationService $tokenVerificationService, SendSMSMessageService $smsService){
        $user = auth()->user();

        $phone = $user->phone;

        if(!preg_match('/0([0-9]){9}/', $phone)){
            return $request->expectsJson() ?
                $this->json->error('Your account profile does not include a valid phone number') :
                back()->withErrors(['status' => 'Your account profile does not include a valid phone number']);
        }

        $code = $tokenVerificationService->createToken($user, config('auth.sms_code_expiry'), 'phone', true);

        if($code == null){
            return $request->expectsJson() ?
                $this->json->error('Oops. Something went wrong') :
                back()->withErrors(['status' => 'Oops. Something went wrong']);
        }

        $code = $code->token;

        // Send code to user
        $msg = 'Hello, use the code '.$code.' to verify your phone number for your '.config('app.name').' account';

        try{
            if($smsService->send($msg, $phone)){
                return $request->expectsJson() ?
                    $this->json->success('We\'ve sent a code on your phone. Enter the code to verify the phone number') :
                    back()->with(['get_code' => true]);
            }
        }catch(Exception $e){}

        return $request->expectsJson() ?
            $this->json->error('Oops. Something went wrong. Please retry') :
            back()->withErrors(['status' => 'Oops. Something went wrong. Please retry']);
    }

    function verifyCode(Request $request, TokenVerificationService $tokenVerificationService){
        $code = $request->post('code');
        $user = auth()->user();

        $token = $tokenVerificationService->retrieveTokenAgainstUser($user, $code);

        if($token == null || $token->isExpired()){
            return back()->withInput()->with(['status_phone' => false]);
        }

        // verify phone
        $verification = $user->verification;
        $verification['official_phone'] = Carbon::now()->toString();

        $user->verification = $verification;

        // verify and delete all email tokens
        DB::beginTransaction();
        if($user->save() && $user->verification_tokens()->where('purpose', 'phone')->delete()){
            DB::commit();
            return back()->withInput()->with(['status_phone' => true]);
        }

        DB::rollback();
        return back()->withInput()->with(['status_phone' => false]);
    }
}
