<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    function signup(Request $request){
        $validator = \validator()->make(array_merge($request->post(), $request->file()), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone' => ['required', 'regex:(0([0-9]){9})'],
            'email' => ['required', 'email', 'unique:users,business->email'],
            'password' => ['required']
        ], [
            'phone.regex' => 'Please provide a valid phone number',
            'password.regex' => 'Password should contain at least 8 characters',
        ]);

        if($validator->fails()){
            return $request->expectsJson() ?
                $this->json->errors($validator->errors()->all()):
                \back()->withInput()->withErrors($validator->errors());
        }

        // Save user
        $user_data = [
            'first_name' => $request->post('first_name'),
            'last_name' => $request->post('last_name'),
            'phone' => $request->post('phone'),
        ];

        $business = [
            'email' => $request->post('email'),
        ];

        $user = User::create([
            'type' => 'client',
            'user' => $user_data,
            'business' => $business,
            'password' => Hash::make($request->post('password'))
        ]);

        if($request->expectsJson()){
            // Create access token

            return $this->json->data($user);
        }else{
            // authenticate and start session
            Auth::login($user, true);
            return \redirect()->route('web.user.dashboard')->with([
                'status' => 'Your account is newly created, go to the verification section of your account and verify your email and phone number. You will not be able to upload ads without completing verification'
            ]);
        }

        return $request->expectsJson() ?
                $this->json->error('Something went wrong. Please try again'):
                back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
    }

    function fullSignup(Request $request){
        $validator = \validator()->make(array_merge($request->post(), $request->file()), [
            'company_name' => ['required', 'string'],
            'official_phone' => ['required', 'regex:(0([0-9]){9,})', 'unique:users,business->phone'],
            'email' => ['required', 'email', 'unique:users,business->email'],
            'kra_pin' => ['required', 'regex:(([A-Z]){1}([0-9]){9}([A-Z]){1})'],
            'kra_pin_document' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf'],
            'business_certificate' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf'],
            'user_name' => ['required', 'string'],
            'phone' => ['required', 'regex:(0([0-9]){9})'],
            'position' => ['required', 'string'],
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
        ], [
            'website.url' => 'Enter a valid website url',
            'phone.regex' => 'Please provide your valid phone number',
            'official_phone.regex' => 'Please provide the valid company\'s official phone number',
            'kra_pin.regex' => 'Please provide a valid KRA Pin',
            'kra_pin_document.required' => "Please upload your company's KRA Pin document",
            'kra_pin_document.mimetypes' => "The company's KRA Pin document should be an image in jpg,jpeg or png or a pdf",
            'kra_pin_document.file' => "The company's KRA Pin document should be an image in jpg,jpeg or png or a pdf",
            'business_certificate.required' => "Please upload your company's certificate of incorporation",
            'business_certificate.mimetypes' => "The certificate of incorporation should be an image in jpg,jpeg or png or a pdf",
            'business_certificate.file' => "The certificate of incorporation should be an image in jpg,jpeg or png or a pdf",
            'password.regex' => 'Password should contain at least 8 characters with at least one letter',
            'confirm_password.same' => 'Passwords do not match!',
        ]);

        if($validator->fails()){
            return $request->expectsJson() ?
                $this->json->errors($validator->errors()->all()):
                \back()->withInput()->withErrors($validator->errors());
        }

        // Save user
        $user_data = [
            'phone' => $request->post('phone'),
            'name' => $request->post('user_name'),
            'position' => $request->post('position'),
        ];

        $business = [
            'name' => $request->post('company_name'),
            'phone' => $request->post('official_phone'),
            'email' => $request->post('email'),
            'kra_pin' => $request->post('kra_pin'),
            'kra_pin_document' => $request->file('kra_pin_document')->store('docs/kra_pins'),
            'business_certificate' => $request->file('business_certificate')->store('docs/certificates'),
        ];

        $user = User::create([
            'type' => 'client',
            'user' => $user_data,
            'business' => $business,
            'password' => Hash::make($request->post('password'))
        ]);

        if($request->expectsJson()){
            // Create access token

            return $this->json->data($user);
        }else{
            // authenticate and start session
            Auth::login($user, $request->boolean('remember_me'));
            return \redirect()->route('web.user.dashboard')->with([
                'status' => 'Your account is newly created, go to the verification section of your account and verify your email and phone number. You will not be able to upload ads without completing verification'
            ]);
        }

        return $request->expectsJson() ?
                $this->json->error('Something went wrong. Please try again'):
                back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
    }
}
