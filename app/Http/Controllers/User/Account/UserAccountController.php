<?php

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAccountController extends Controller
{
    function completeProfile(Request $request){
        $validator = \validator()->make(array_merge($request->post(), $request->file()), [
            'name' => ['required', 'string'],
            'kra_pin' => ['required', 'regex:(([A-Z]){1}([0-9]){9}([A-Z]){1})'],
            'document_kra_pin' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf'],
            'document_certificate' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf'],
            'position' => ['required', 'string'],
        ], [
            'name' => 'Please provide your company_name',
            'kra_pin.regex' => 'Please provide a valid KRA Pin',
            'document_kra_pin.required' => "Please upload your company's KRA Pin document",
            'document_kra_pin.mimes' => "The company KRA Pin document should be an image in jpg,jpeg or png or a pdf",
            'document_kra_pin.file' => "The company KRA Pin document should be an image in jpg,jpeg or png or a pdf",
            'document_certificate.required' => "Please upload your company's certificate of incorporation",
            'document_certificate.mimes' => "The certificate of incorporation should be an image in jpg,jpeg or png or a pdf",
            'document_certificate.file' => "The certificate of incorporation should be an image in jpg,jpeg or png or a pdf",
        ]);

        if($validator->fails()){
            return $request->expectsJson() ?
                $this->json->errors($validator->errors()->all()):
                \back()->withInput()->withErrors($validator->errors());
        }

        $user = Auth::user();

        $user_info = $user->user;

        $user_info['position'] = $request->post('position');

        $user->user = $user_info;

        $business = $user->business;
        $business = array_merge($business, [
            'name' => $request->post('name'),
            'kra_pin' => $request->post('kra_pin'),
            'kra_pin_document' => $request->file('document_kra_pin')->store('docs/kra_pins'),
            'business_certificate' => $request->file('document_certificate')->store('docs/certificates'),
        ]);

        $user->business = $business;

        if($user->save()){
            return $request->expectsJson() ?
                $this->json->success('Your profile has been updated') :
                redirect()->intended(route('web.user.account'))->with(['status' => 'Your profile has been updated']);
        }

        return $request->expectsJson() ?
            $this->json->error('Something went wrong') :
            back()->withInput()->withErrors(['status' => 'Something went wrong']);
    }

    function updateInfo(Request $request){
        $validator = \validator()->make(array_merge($request->post(), $request->file()), [
            'phone' => ['required', 'regex:(0([0-9]){9})']
        ], [
            'phone.regex' => 'Please provide your valid phone number',
        ]);

        if($validator->fails()){
            return $request->expectsJson() ?
                $this->json->errors($validator->errors()->all()):
                \back()->withInput()->withErrors($validator->errors());
        }

        $user = Auth::user();

        $user_info = $user->user;

        $user_info['phone'] = $request->post('phone');

        $user->user = $user_info;

        if($user->save()){
            return $request->expectsJson() ?
                $this->json->success('Changes have been saved successfully') :
                back()->with(['status' => 'Changes have been saved successfully']);
        }

        return $request->expectsJson() ?
            $this->json->error('Something went wrong') :
            back()->withInput()->withErrors(['status' => 'Something went wrong']);
    }

    function updatePassword(Request $request){
        $validator = Validator::make($request->post(), [
            'password' => ['required'],
            'new_password' => ['required'],
            'confirm_password' => ['required', 'same:new_password']
        ],[
            'new_password.regex' => 'Password should contain at least 8 characters with at least one letter',
            'confirm_password.same' => 'Passwords do not match!'
        ]);

        if($validator->fails()){
            return $request->expectsJson() ?
            $this->json->errors($validator->errors()->all()) :
            back()->withInput()->withErrors($validator->errors());
        }

        // current user
        $user = auth()->user();

        // reauthenticate
        if($user->isClient()) $col = 'business->email';
        else $col = 'email';

        if(!auth()->validate([$col => $user->email, 'password' => $request->post('password')])){
            return $request->expectsJson() ?
                $this->json->error('Unable to authenticate you') :
                back()->withInput()->withErrors(['status' => 'Unable to authenticate you']);
        }

        // Change password
        $user->password = Hash::make($request->post('new_password'));

        if($user->save()){
            return $request->expectsJson() ?
                $this->json->success('New password has been saved, next time you log in, use it') :
                back()->with(['status' => 'New password has been saved, next time you log in, use it']);
        }

        return $request->expectsJson() ?
            $this->json->error('Something went wrong') :
            back()->withInput()->withErrors(['status' => 'Something went wrong']);
    }
}
