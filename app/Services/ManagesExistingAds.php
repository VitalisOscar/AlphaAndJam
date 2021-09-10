<?php

namespace App\Services;

use App\Models\Advert;
use App\Models\Slot;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait ManagesExistingAds{
    /**
     * @param Advert $ad
     * @param array $data
     * @param UploadedFile null $media
     * @return bool
     */
    function modifyAd($ad, array $data, $media = null){
        // Can only edit unsubmitted and rejected ads
        if(!in_array($ad->status, [Advert::STATUS_PENDING_PAYMENT, Advert::STATUS_PAYMENT_FAILED, Advert::STATUS_DECLINED])){
            // return false;
        }

        // If new media is being uploaded, delete the old one
        $old_path = $media != null ? $ad->content['media_path'] : null;
        
        $media_type = strtolower(explode("/", $media->getMimeType())[0]);

        $media_path = $ad->content['media_path'];

        if($media != null){
            $path = $this->getUploadDir($media_type);

            // Upload media
            $media_path = $media->store($path, 'public');
        }else{
            $media_type = $ad->content['media_type'];
            $media_path = $ad->content['media_path'];
        }

        $content = [
            'text' => isset($data['text']) ? $data['text']:null,
            'phone' => isset($data['phone']) ? $data['phone']:null,
            'email' => isset($data['email']) ? $data['email']:null,
            'media_type' => $media_type,
            'media_path' => $media_path,
        ];

        $ad->category_id = $data['category_id'];
        $ad->description = $data['description'];
        $ad->content = $content;

        // If ad had been declined, make it pending reapproval
        if($ad->status == Advert::STATUS_DECLINED){
            $ad->status = Advert::STATUS_PENDING_REAPPROVAL;
        }

        // DB::beginTransaction();

        if($ad->save()){
            // // Update changed slots
            // $slots = $this->optimizeSlots($slots);

            // foreach ($slots as $slot) {
            //     $s = Slot::where('id', $slot->id)
            //             ->where('advert_id', $ad->id)
            //             ->first();

            //     if($s == null || !$this->slotIsAvailable($slot) || !$slot->save()){
            //         DB::rollBack();
            //         return false;
            //     }
            // }

            // DB::commit();

            // Delete old media
            if($old_path) {
                Storage::disk('public')->delete($old_path);
            }

            // DB::commit();
            return true;
        }

        // DB::rollBack();
        return false;
    }

    /**
     * Delete an ad
     * @param Advert $ad
     */
    function deleteAd($ad){
        if($ad == null) return true;

        try{
            return $ad->delete();
            return true;
        }catch(Exception $e){
            return false;
        }
    }
}
