<?php

namespace App\Repository;

use App\Helpers\ResultSet;
use App\Models\Advert;
use App\Models\Category;
use Carbon\Carbon;

class AdminAdvertRepository{

    /**
     * Get recently uploaded ads
     * @param int $limit Fetch limit
     * @return Advert[]
     */
    function getRecent($limit = 5){
        $ads = Advert::orderBy('created_at', 'desc')
            ->limit($limit)
            ->whereHas('category')
            ->with(['user', 'category'])
            ->get()
            ->each(function($ad){
                $this->modifyResult($ad, true);
            });

        return $ads;
    }

    /**
     * Get query for current url
     */
    function getQuery($status){
        $query = Advert::query()->whereHas('category');
        $request = request();

        // by client
        if($request->filled('client')){
            $query->where('user_id', $request->get('client'));
        }

        // Status
        if($status == null){
            $s = $request->get('status');

            if($s == 'approved') $status = Advert::STATUS_APPROVED;
            else if($s == 'rejected') $status = Advert::STATUS_DECLINED;
            else if($s == 'pending') $status = [Advert::STATUS_PENDING_APPROVAL, Advert::STATUS_PENDING_REAPPROVAL];
            else $status = [Advert::STATUS_APPROVED, Advert::STATUS_DECLINED, Advert::STATUS_PENDING_APPROVAL, Advert::STATUS_PENDING_REAPPROVAL];
        }

        if($status != null){
            if(\is_array($status)){
                $query->whereIn('status', $status);
            }else{
                $query->where('status', $status);
            }
        }

        // Category
        $category = $request->get('category');
        if($category != null) $query->where('category_id', $category);

        // Ordering
        $order = $request->get('order', 'recent');

        if(!in_array($order, ['recent', 'oldest'])) $order = 'recent';

        if($order == 'recent') $query->orderBy('created_at', 'desc');
        else $query->orderBy('created_at', 'asc');

        return $query->with('category')->withCount('slots');
    }

    /**
     * Get a user's ads
     */
    function getAll($status = null){
        return new ResultSet($this->getQuery($status), Advert::USER_FETCH_LIMIT, function($ad){
            $this->modifyResult($ad);
        });
    }

    /**
     * Get a single ad detail
     * @param int $id
     * @return Advert|null
     */
    function getSingle($id){
        return $this->modifyResult(Advert::where('id', $id)
            ->whereHas('category')
            ->with(['category', 'slots', 'invoice'])
            ->first()
        );
    }

    /**
     * Modify a fetched ad
     */
    function modifyResult($ad, $brief = false){
        if($ad != null){
            $ad->category_name = $ad->category->name;

            // Date
            $time = Carbon::createFromTimeString($ad->created_at);
            $now = Carbon::now();

            if($time->isToday()){
                $mins_ago = $time->diffInMinutes($now);
                if($mins_ago < 60){
                    if($mins_ago == 0){
                        $ad->time = 'Just now';
                    }else{
                        $ad->time = $mins_ago.' min ago';
                    }
                }else{
                    $hours_ago = $time->diffInHours($now);
                    $ad->time = $hours_ago.' hour'.($hours_ago > 1 ? 's':'').' ago';
                }

                return $ad;
            }else if($time->isYesterday()){
                $ad->time = "Yesterday";
            }else{
                $ad->time = substr($time->monthName, 0, 3)." ".$time->day.", ".$time->year;
            }

            if(!$brief){
                $ad->time .= ' '.($time->hour > 12 ? ($time->hour - 12):$time->hour).':'.($time->minute < 10 ? '0':'').$time->minute.' '.(($time->hour > 12) ? 'PM': ($time->hour == 12 ? 'Noon':'AM'));
            }

            return $ad;
        }
    }
}
