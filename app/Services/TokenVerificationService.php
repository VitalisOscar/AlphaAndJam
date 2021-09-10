<?php

namespace App\Services;

use App\Models\VerificationToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TokenVerificationService{
    /**
     * Create a new verification token
     * @param User $user User to associate token with
     * @param int $validity Validity of token, in minutes
     * @param string $purpose Verification purpose
     * @param bool $code Use a code instead
     * @return VerificationToken|null
     */
    function createToken($user, $validity, $purpose, $code = false){
        // Create token
        if($code){
            $hash = rand(100000, 999999);
        }else{
            $hash = hash_hmac('sha256', uniqid().rand(1,1000), config('app.key'));
        }

        // time
        $created = Carbon::now();
        $expiry = Carbon::now()->addMinutes($validity);

        $token = new VerificationToken();
        $token->user_id = $user->id;
        $token->purpose = $purpose;
        $token->token = $hash;
        $token->created_at = $created;
        $token->expires_at = $expiry;

        if($token->save()){
            return $token;
        }

        return false;
    }

    /**
     * Retrieve a token from db
     * @param string $user_token Token provided in link
     * @return VerificationToken|null
     */
    function retrieveToken($user_token){
        $token = VerificationToken::where('token', $user_token)->first();

        return $token;
    }

    /**
     * Retrieve a token from db, using user and token or code
     * @param User $user User to associate with token
     * @param string $user_token Token or code provided
     * @return VerificationToken|null
     */
    function retrieveTokenAgainstUser($user, $user_token){
        $token = VerificationToken::where('user_id', $user->id)->where('token', $user_token)->first();

        return $token;
    }

    /**
     * Delete related tokens for user
     * @param User $user
     * @param string $purpose
     */
    function deleteAll($user, $purpose){
        $user->verification_tokens()->where('purpose', $purpose)->delete();
    }

}
