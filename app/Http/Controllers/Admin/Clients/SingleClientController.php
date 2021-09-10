<?php

namespace App\Http\Controllers\Admin\Clients;

use App\Http\Controllers\Controller;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use App\Models\StaffLog;
use App\Models\Notification;
use App\Models\User;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SingleClientController extends Controller
{
    function get($email){
        $client = User::client()->where('business->email', $email)->first();

        return response()->view('admin.clients.single', [
            'client' => $client
        ]);
    }

    function viewKraPin($email){
        $client = User::client()->where('business->email', $email)->first();

        $doc = $client->kra_pin_document;
        if($doc == null){
            return back()->withErrors(['status' => 'KRA Pin document for this client was not uploaded during registration']);
        }

        return response()->file(storage_path($doc));
    }

    function viewCertificate($email){
        $client = User::client()->where('business->email', $email)->first();

        $doc = $client->business_certificate;
        if($doc == null){
            return back()->withErrors(['status' => 'Business/Incorporation certificate for this client was not uploaded during registration']);
        }

        return response()->file(storage_path($doc));
    }

    function verify(MailService $mail, $email){
        $client = User::client()->where('business->email', $email)->first();

        if($client->isVerified()){
            return back()->withErrors([
                'status' => 'Client account is already verified'
            ]);
        }

        $verification = $client->verification;

        $verification['business'] = Carbon::now()->toString();
        if(isset($verification['rejected'])){
            $verification['rejected'] = null;
        }

        $client->verification = $verification;

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Verified client account for '".$client->name."'",
            'item' => StaffLog::ITEM_USER,
            'item_id' => $client->id
        ]);

        // create notification
        $notification = new Notification([
            'user_id' => $client->id,
            'title' => 'Account Approved',
            'content' => "Your account has been approved. You can now submit your content to us straight away from your phone, tablet or PC",
            'item' => Notification::ITEM_USER,
            'item_id' => $client->id
        ]);

        DB::beginTransaction();

        if(!($client->save() && $log->save() && $notification->save())){
            DB::rollback();
            return back()->withErrors(['status' => 'Something went wrong. Please try again']);
        }

        DB::commit();

        // send email
        $mail->send(new AccountApprovedMail($client));

        return back()->with(['status' => 'Client account is now verified']);
    }

    function reject(MailService $mail, $email){
        $client = User::client()->where('business->email', $email)->first();

        if($client->isRejected()){
            return back()->withErrors([
                'status' => 'Client account is already rejected'
            ]);
        }

        $verification = $client->verification;

        $verification['business'] = null;
        $verification['rejected'] = true;

        $client->verification = $verification;

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Rejected client account for '".$client->name."'",
            'item' => StaffLog::ITEM_USER,
            'item_id' => $client->id
        ]);

        // create notification
        $notification = new Notification([
            'user_id' => $client->id,
            'title' => 'Account Declined',
            'content' => "Your account details could not be verified. Until then, you cannot submit your content to us on the platform",
            'item' => Notification::ITEM_USER,
            'item_id' => $client->id
        ]);

        DB::beginTransaction();

        if(!($client->save() && $log->save() && $notification->save())){
            DB::rollback();
            return back()->withErrors(['status' => 'Something went wrong. Please try again']);
        }

        DB::commit();

        // send email
        $mail->send(new AccountRejectedMail($client));

        return back()->with(['status' => 'Client account has been declined']);
    }

    function addPostPay(Request $request, $email){
        $validator = validator()->make($request->post(), [
            'limit' => ['required', 'numeric', 'min:0']
        ]);

        if($validator->fails()) return back()->withErrors(['status' => 'Please specify a valid limit for the client']);

        $client = User::client()->where('business->email', $email)->first();

        $payment = $client->payment;
        $payment['post_pay'] = true;
        $payment['post_pay_limit'] = $request->post('limit');
        $client->payment = $payment;

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Made client account '".$client->name."' a post pay client",
            'item' => StaffLog::ITEM_USER,
            'item_id' => $client->id
        ]);

        // create notification
        $notification = new Notification([
            'user_id' => $client->id,
            'title' => 'Added to Post Pay',
            'content' => "Your account has been added to post pay clients. You'll be able to pay for your invoices even after your ads have been shown",
            'item' => Notification::ITEM_USER,
            'item_id' => $client->id
        ]);

        if(!($client->save() && $log->save() && $notification->save())) return back()->withErrors(['status' => 'Something went wrong. Please try again']);

        // Send email

        return back()->with(['status' => 'Account has been added to post pay clients']);
    }

    function removePostPay($email){
        $client = User::client()->where('business->email', $email)->first();

        $payment = $client->payment;
        $payment['post_pay'] = false;
        $payment['post_pay_limit'] = 0;
        $client->payment = $payment;

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Removed client account '".$client->name."' from post pay clients",
            'item' => StaffLog::ITEM_USER,
            'item_id' => $client->id
        ]);

        // create notification
        $notification = new Notification([
            'user_id' => $client->id,
            'title' => 'Removed from Post Pay',
            'content' => "Your account has been removed from post pay clients. You'll have to pay for your invoices before your ads are shown",
            'item' => Notification::ITEM_USER,
            'item_id' => $client->id
        ]);

        if(!($client->save() && $log->save() && $notification->save())) return back()->withErrors(['status' => 'Something went wrong. Please try again']);

        // TODO send user notification
        return back()->with(['status' => 'Account has been removed from post pay clients']);
    }
}
