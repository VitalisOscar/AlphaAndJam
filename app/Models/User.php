<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const LOGO_DIR = 'logos';
    const DOCUMENTS_DIR = 'docs';

    const MAX_LOGO_SIZE = '500k';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'user',
        'business',
        'password',
        'verification'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'user' => 'array',
        'business' => 'array',
        'verification' => 'array',
        'payment' => 'array',
    ];

    function isClient(){ return $this->type == 'client'; }

    function isAgent(){ return $this->type == 'agent'; }

    function scopeAgent($q){
        $q->whereType('agent');
    }

    function scopeClient($q){
        $q->whereType('client');
    }

    function scopeApproved($q){
        $q->where('verification->business', '<>', null);
    }

    function scopePending($q){
        $q->where('verification->business', null);
    }

    function scopeRejected($q){
        $q->where(function($q1){
            $q1->where('verification->rejected', true);
        });
    }

    function adverts(){
        return $this->hasMany(Advert::class);
    }

    function invoices(){
        return $this->hasManyThrough(Invoice::class, Advert::class);
    }

    function notifications(){
        return $this->hasMany(Notification::class);
    }

    function verification_tokens(){
        return $this->hasMany(VerificationToken::class);
    }

    function getEmailAttribute(){
        return $this->business['email'];
    }

    function getKraPinAttribute(){
        return $this->business['kra_pin'] ?? 'Not Provided';
    }

    function getPhoneAttribute(){
        return $this->operator_phone;
    }

    function getNameAttribute(){
        return $this->business['name'] ?? $this->operator_name;
    }

    function getOperatorNameAttribute(){
        if(isset($this->user['name'])){
            return $this->user['name'];
        }

        if(isset($this->user['first_name'], $this->user['last_name'])){
            return $this->user['first_name'].' '.$this->user['last_name'];
        }

        return $this->user['first_name'] ?? $this->user['last_name'] ?? 'Unknown';
    }

    function getOperatorPhoneAttribute(){
        return $this->user['phone'] ?? 'Not Provided';
    }

    function getOperatorPositionAttribute(){
        return $this->user['position'] ?? 'Position Not Provided';
    }

    function getTimeAttribute(){
        $time = $this->getAttribute('registered_at');

        $time = Carbon::createFromTimeString($time);
        $now = Carbon::now();

        if($time->isToday()){
            $mins_ago = $time->diffInMinutes($now);
            if($mins_ago < 60){
                if($mins_ago == 0){
                    return 'Just now';
                }else{
                    return $mins_ago.' min ago';
                }
            }else{
                $hours_ago = $time->diffInHours($now);
                return $hours_ago.' hour'.($hours_ago > 1 ? 's':'').' ago';
            }
        }else if($time->isYesterday()){
            return "Yesterday";
        }else{
            return substr($time->monthName, 0, 3)." ".$time->day.", ".$time->year;
        }
    }

    function getKraPinDocumentAttribute(){
        $business = $this->getAttribute('business');
        return isset($business['kra_pin_document']) ? 'app/'.$business['kra_pin_document'] : null;
    }

    function getBusinessCertificateAttribute(){
        $business = $this->getAttribute('business');
        return isset($business['business_certificate']) ? 'app/'.$business['business_certificate'] : null;
    }

    function getPostPayLimitAttribute(){
        $payment = $this->getAttribute('payment');
        return isset($payment['post_pay_limit']) ? $payment['post_pay_limit'] : 0;
    }

    function canPayLater(){
        $payment = $this->getAttribute('payment');
        return (isset($payment['post_pay']) && $payment['post_pay']);
    }

    function isVerified(){
        $verification = $this->getAttribute('verification');
        return (isset($verification['business']));
    }

    function isRejected(){
        $verification = $this->getAttribute('verification');
        return (isset($verification['rejected']) && $verification['rejected']);
    }

    function isPending(){
        $verification = $this->getAttribute('verification');
        return (!isset($verification['business']) && !isset($verification['rejected']));
    }

    function profileComplete(){
        return isset(
            $this->user['position'],
            $this->business['name'],
            $this->business['kra_pin'],
        );
    }
}
