<?php

namespace App\Http\Controllers\User\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewAdvertRequest;
use App\Repository\AdvertRepository;
use App\Services\AdService;

class CreateAdvertController extends Controller
{
    function create(NewAdvertRequest $request, AdService $ad_service){
        $ad = $ad_service->createNewAd($request->validated(), $request->file('media'));

        if($ad){
            return $this->json->data(['ad' => $ad]);
        }

        return $this->json->error('Something went wrong');
    }

    function getAdForRecreate(AdvertRepository $repository, $id){
        $old_ad = $repository->getSingle($id);

        if($old_ad == null){
            return \response()->view('web.ads._ad_not_exist');
        }else{
            return \response()->view('web.ads.edit', [
                'advert' => $old_ad,
                'title' => 'Create advert',
                'heading' => 'Get Started',
                'mode' => 'create'
            ]);
        }
    }

    function recreate(NewAdvertRequest $request, AdService $ad_service, AdvertRepository $repository, $id = null){
        if($id == null) $id = $request->post('advert_id');

        $old_ad = $repository->getSingle($id);

        if($old_ad == null){
            $ad = $ad_service->createNewAd($request->validated(), $request->file('media'));
        }else{
            $ad = $ad_service->reCreateAd($old_ad, $request->validated(), $request->file('media'));
        }

        // Check if successful
        if($ad){
            return $this->json->data(['ad' => $ad]);
        }

        return $this->json->error('Something went wrong');
    }
}
