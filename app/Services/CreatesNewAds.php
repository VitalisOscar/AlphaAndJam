<?php

namespace App\Services;

use App\Models\Advert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

trait CreatesNewAds{
    use ManagesSlots;

    /**
     * Create a new ad
     * @param array $data
     * @param UploadedFile null $media
     * @return Advert|bool
     */
    function createNewAd($data, $media = null){
        $media_type = ($media != null) ? strtolower(explode("/", $media->getClientMimeType())[0]) : null;

        $media_path = null;
        if($media_type != null){
            $path = $this->getUploadDir($media_type);

            // Upload media
            $media_path = $media->store($path, 'public');
        }

        return $this->saveNewAd($data, $media_type, $media_path);
    }

    /**
     * Create a new ad from existing ad
     * @param Advert $old_ad
     * @param array $data
     * @param UploadedFile null $media
     * @return Advert|bool
     */
    function reCreateAd($old_ad, $data, $media = null){
        if($media == null){
            // Use old ad's media
            $media_type = $old_ad->content['media_type'];
            $media_path = $old_ad->content['media_path'];
        }else{
            $media_type = strtolower(explode("/", $media->getMimeType())[0]);
            $path = $this->getUploadDir($media_type);

            // Upload media
            $media_path = $media->store($path, 'public');
        }

        return $this->saveNewAd($data, $media_type, $media_path);
    }

    private function saveNewAd($data, $media_type, $media_path){
        $slots = $this->getSlotsFromRequest($data);

        $content = [
            'media_type' => $media_type,
            'media_path' => $media_path,
        ];

        // Save to db
        DB::beginTransaction();

        $ad = new Advert();

        $ad->category_id = $data['category_id'];

        // User
        $user = auth()->user();
        $ad->user_id = $user->id;

        $ad->description = $data['description'];
        $ad->content = $content;

        // Ad status
        // Pending payment for regular clients
        // Pending approval for clients with post pay privilleges
        // if(auth()->user()->canPayLater()){
        //     $ad->status = Advert::STATUS_PENDING_APPROVAL;
        // }else{
        //     $ad->status = Advert::STATUS_PENDING_PAYMENT;
        // }

        // All must be approved first
        $ad->status = Advert::STATUS_PENDING_APPROVAL;

        if(!$ad->save()){
            return false;
        }

        $slots = $this->optimizeSlots($slots);

        $price = 0;

        foreach ($slots as $slot) {
            $slot->advert_id = $ad->id;
            $price += $slot->price;

            if(!$this->slotIsAvailable($slot) || !$slot->save()){
                DB::rollBack();
                return false;
            }
        }

        // Invoice
        // $invoice = $this->createInvoice($ad->id, $price);
        // if(!$invoice->save()){
        //     DB::rollBack();
        //     return false;
        // }
        // will be generated after approval

        DB::commit();

        // Add invoice
        // $ad->invoice_number = $invoice->number;

        // Price
        $ad->price = number_format($price);

        return $ad;
    }
}
