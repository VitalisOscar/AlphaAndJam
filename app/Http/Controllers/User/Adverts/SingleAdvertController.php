<?php

namespace App\Http\Controllers\User\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewAdvertRequest;
use App\Repository\AdvertRepository;
use App\Services\AdService;
use Illuminate\Http\Request;

class SingleAdvertController extends Controller
{
    function getAd(Request $request, $id){
        $repository = \resolve(\App\Repository\AdvertRepository::class);
        $ad = $repository->getSingle($id);

        if($ad == null){
            return \response()->view('web.ads._ad_not_exist');
        }

        $ad->load('slots.screen');

        return $request->expectsJson() ?
            $this->json->data($ad):
            \response()->view('web.ads.edit', [
                'advert' => $ad,
                'title' => 'Edit advert',
                'heading' => 'Edit Advert',
                'mode' => 'edit'
            ]);
    }

    function editAd(NewAdvertRequest $request, AdvertRepository $repository, AdService $ad_service, $id = null){
        if($id == null) $id = $request->post('advert_id');

        $ad = $repository->getOriginal($id);

        if($ad == null){
            return $request->expectsJson() ?
                $this->json->error("Ad does not exist or you cannot edit it"):
                \back()->withInput()->withErrors(['status' => 'Ad does not exist or you cannot edit it']);
        }

        if($ad_service->modifyAd($ad, $request->validated(), $request->file('media'))){
            return $request->expectsJson() ?
                $this->json->success("Advert operation successful"):
                \redirect()->route('web.adverts.single', $ad->id);
        }

        return $request->expectsJson() ?
                $this->json->error("An error occurred or you cannot edit the ad in it\'s current status"):
                \back()->withInput()->withErrors(['status' => "An error occurred or you cannot edit the ad in it\'s current status"]);
    }

    function deleteAd(Request $request, AdvertRepository $repository, AdService $ad_service, $id){
        if($ad_service->deleteAd($repository->getSingle($id))){
            return $request->expectsJson() ?
            $this->json->success('Advert deleted from your history'):
            redirect()->route('web.adverts.drafts');
        }
    }

    function addMedia(){

    }
}
