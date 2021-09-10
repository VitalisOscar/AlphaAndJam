<?php

namespace App\Http\Controllers\Admin\Agents;

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

class SingleAgentController extends Controller
{
    function get($agent_id){
        $agent = User::agent()->whereId($agent_id)->first();

        return response()->view('admin.agents.single', [
            'agent' => $agent
        ]);
    }

    function approve(MailService $mail, $agent_id){
        $agent = User::agent()->whereId($agent_id)->first();

        if($agent->isVerified()){
            return back()->withErrors([
                'status' => 'The agent is already approved'
            ]);
        }

        $verification = $agent->verification;
        unset($verification['rejected']);
        $verification['business'] = Carbon::now()->toTimeString();

        $agent->verification = $verification;

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Verified agent account for '".$agent->name."'",
            'item' => StaffLog::ITEM_AGENT,
            'item_id' => $agent->id
        ]);

        // create notification
        $notification = new Notification([
            'user_id' => $agent->id,
            'title' => 'Account Approved',
            'content' => "Your agent account is now approved. You can now submit advertising content on behalf of other clients from your phone, tablet or PC",
            'item' => Notification::ITEM_AGENT,
            'item_id' => $agent->id
        ]);

        DB::beginTransaction();

        if(!($agent->save() && $log->save() && $notification->save())){
            DB::rollback();
            return back()->withErrors(['status' => 'Something went wrong. Please try again']);
        }

        DB::commit();

        // send email
        $mail->send(new AccountApprovedMail($agent));

        return back()->with(['status' => 'Agent account is now approved']);
    }

    function reject(MailService $mail, $agent_id){
        $agent = User::agent()->whereId($agent_id)->first();

        if($agent->isRejected()){
            return back()->withErrors([
                'status' => 'The agent account is already deactivated'
            ]);
        }

        $verification = $agent->verification;
        $verification['rejected'] = true;
        unset($verification['business']);

        $agent->verification = $verification;


        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Rejected agent account for '".$agent->name."'",
            'item' => StaffLog::ITEM_AGENT,
            'item_id' => $agent->id
        ]);

        // create notification
        $notification = new Notification([
            'user_id' => $agent->id,
            'title' => 'Account Declined',
            'content' => "Your agent account is no longer active. You can therefore not submit content to us on the platform for other clients",
            'item' => Notification::ITEM_AGENT,
            'item_id' => $agent->id
        ]);

        DB::beginTransaction();

        if(!($agent->save() && $log->save() && $notification->save())){
            DB::rollback();
            return back()->withErrors(['status' => 'Something went wrong. Please try again']);
        }

        DB::commit();

        // send email
        $mail->send(new AccountRejectedMail($agent));

        return back()->with(['status' => 'Agent account has been deactivated']);
    }
}
