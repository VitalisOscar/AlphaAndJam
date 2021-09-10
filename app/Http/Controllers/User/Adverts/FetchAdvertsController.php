<?php

namespace App\Http\Controllers\User\Adverts;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Repository\AdvertRepository;
use Illuminate\Http\Request;

class FetchAdvertsController extends Controller
{
    function getByStatus($status, $title, $header, $description){
        $repository = \resolve(\App\Repository\AdvertRepository::class);

        return \response()->view('web.ads.list', [
            'title' => $title,
            'header' => $header,
            'description' => $description,
            'result' => $repository->getAll($status)
        ]);
    }

    function drafts(){
        return $this->getByStatus(
            [Advert::STATUS_PENDING_PAYMENT, Advert::STATUS_PAYMENT_FAILED],
            'Drafts',
            'Drafts',
            'Ads on this page have been saved but payment has not been completed or it failed'
        );
    }

    function pending(){
        return $this->getByStatus(
            [Advert::STATUS_PENDING_APPROVAL, Advert::STATUS_PENDING_REAPPROVAL],
            'Pending',
            'Pending Approval',
            'Ads on this page have been submitted but are yet to be approved by our staff for airing'
        );
    }

    function approved(){
        return $this->getByStatus(
            Advert::STATUS_APPROVED,
            'Approved',
            'Approved Ads',
            'Ads on this page have been approved. If payment has been completed, the ads will be aired on the booked screens on respective dates and time'
        );
    }

    function declined(){
        return $this->getByStatus(
            Advert::STATUS_DECLINED,
            'Declined',
            'Declined ads',
            'Ads on this page do not meet our set standards and cannot be aired. You can edit individual ads and resubmit again'
        );
    }

    function mixed(AdvertRepository $repository){
        $result = $repository->getAll();

        $adverts = $result['adverts'];
        unset($result['adverts']);

        return $this->json->mixed($result, $adverts);
    }

    function single(Request $request, $id = null){
        if($id == null) $id = $request->get('advert_id');

        $repository = \resolve(\App\Repository\AdvertRepository::class);
        $ad = $repository->getSingle($id);

        if($ad == null){
            return \response()->view('web.ads._ad_not_exist');
        }

        return $request->expectsJson() ?
            $this->json->data($ad):
            \response()->view('web.ads.single', [
                'advert' => $ad
            ]);
    }
}
