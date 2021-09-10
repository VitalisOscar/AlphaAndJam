<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Mail\AdvertApprovedMail;
use App\Mail\AdvertRejectedMail;
use App\Models\Advert;
use App\Models\Notification;
use App\Models\StaffLog;
use App\Services\MailService;
use App\Services\PaymentService;
use App\Services\SendSMSMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SingleAdvertController extends Controller
{
    function approve(PaymentService $paymentService, SendSMSMessageService $sms, MailService $mail, $id)
    {
        $advert = Advert::where('id', $id)->with(['user'])->first();

        // Approve ad
        $advert->status = Advert::STATUS_APPROVED;

        // Create invoice
        $invoice = $paymentService->createInvoice($advert, $paymentService->getAdvertPrice($advert));

        // create notifications
        // Ad status
        $ad_notification = new Notification([
            'user_id' => $advert->user_id,
            'title' => 'Advert Approved',
            'content' => "Your advert, '$advert->description', has been approved for airing",
            'item' => Notification::ITEM_ADVERT,
            'item_id' => $advert->id
        ]);

        // New Invoice
        $invoice_notification = new Notification([
            'user_id' => $advert->user_id,
            'title' => 'New Invoice',
            'content' => "A new invoice for your advert, '$advert->description', has been generated. Please check your invoices in your account and finish payment",
            'item' => Notification::ITEM_INVOICE
        ]);

        // create admin log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Approved advert '" . $advert->description . "'",
            'item' => StaffLog::ITEM_ADVERT,
            'item_id' => $advert->id
        ]);

        DB::beginTransaction();
        if ($invoice->save() && $advert->save() && $ad_notification->save() && $log->save()) {
            $invoice_notification->item_id = $invoice->id;

            if ($invoice_notification->save()) {
                DB::commit();
                // notify user
                // send emails
                $mail->send(new AdvertApprovedMail($advert->user, $advert->invoice, $advert));

                // send sms
                $sms->send('Your ad, \'' . $advert->description . '\' has been approved. Log onto your ' . config('app.name') . ' account and find it under Approved Ads section', $advert->user->phone);

                return back()->with([
                    'status' => 'Advert has been approved. Client has been notified by the system'
                ]);
            }
        }

        DB::rollback();
        return back()->withErrors([
            'status' => 'Something went wrong. Please try again'
        ]);
    }

    function reject(Request $request, SendSMSMessageService $sms, MailService $mail, $id)
    {
        if (!$request->filled('reason')) {
            return back()->withErrors([
                'status' => 'Please specify a reason for declining ad'
            ]);
        }

        $advert = Advert::where('id', $id)->first();
        $reason = $request->post('reason');

        $advert->status = Advert::STATUS_DECLINED;

        // create admin log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Rejected advert '" . $advert->description . "' with reason, '" . $reason . "'",
            'item' => StaffLog::ITEM_ADVERT,
            'item_id' => $advert->id
        ]);

        // create notification
        $ad_notification = new Notification([
            'user_id' => $advert->user_id,
            'title' => 'Advert Declined',
            'content' => "Your advert, '$advert->description', has been declined by our moderators since it does not meet some of our set standards. Check your email for more info and a reason why",
            'item' => Notification::ITEM_ADVERT,
            'item_id' => $advert->id
        ]);

        DB::beginTransaction();
        if ($advert->save() && $ad_notification->save() && $log->save()) {
            DB::commit();
            // send email
            $mail->send(new AdvertRejectedMail($advert->user, $advert, $reason));

            // send sms
            $sms->send('Your ad, \'' . $advert->description . '\' has been declined. Check your email for details about why this happenned', $advert->user->phone);

            return back()->with([
                'status' => 'Advert has been declined. Client has been notified by the system'
            ]);
        }

        DB::rollback();

        return back()->withErrors([
            'status' => 'Something went wrong. Please try again'
        ]);
    }
}
