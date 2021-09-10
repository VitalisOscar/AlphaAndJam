<?php

namespace App\Repository;

use App\Models\Advert;
use Carbon\Carbon;

class AdvertRepository{
    /**
     * Get user's recent ads
     * @param int $limit Fetch limit
     * @return Advert[]
     */
    function getRecentAds($limit = 5){
        $user = auth()->user();

        $ads = $user->adverts()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->with(['category'])
            ->withCount('slots')
            ->whereHas('category')
            ->whereHas('slots', function($q){
                $q->whereHas('screen')->whereHas('package');
            })
            ->get()
            ->each(function($ad){
                $this->modifyResult($ad);
            });

        return $ads;
    }

    /**
     * Get a single ad detail
     * @param int $id
     * @return Advert|null
     */
    function getSingle($id){
        $user = auth()->user();

        return $this->modifyResult($user->adverts()
            ->whereId($id)
            ->whereHas('category')
            ->whereHas('slots', function($q){
                $q->whereHas('screen')->whereHas('package');
            })
            ->with(['category', 'slots'])
            ->first()
        );
    }

    /**
     * Get a single ad detail, unmodified
     * @param int $id
     * @return Advert|null
     */
    function getOriginal($id){
        $user = auth()->user();

        return $user->adverts()
            ->where('id', $id)
            ->whereHas('category')
            ->whereHas('slots', function($q){
                $q->whereHas('screen')->whereHas('package');
            })
            ->with(['category', 'slots'])
            ->first();
    }

    /**
     * Get a user's ads
     */
    function getAll($status = null){
        $user = auth()->user();

        $query = $user->adverts()
            ->whereHas('category')
            ->whereHas('slots', function($q){
                $q->whereHas('screen')->whereHas('package');
            });

        $request = request();

        // Status
        if($status == null){
            $status = $request->get('status');
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

        // Media options
        $media = $request->get('media');

        if($media != null){
            if(!is_array($media)) $media = [$media];

            $query->whereIn('content->media_type', $media);
        }

        // Get total maximum no of ads
        $total = $query->count();

        // Pagination
        $page = intval($request->get('page', 1));
        if($page < 1) $page = 1;

        $limit = Advert::USER_FETCH_LIMIT;
        $offset = ($page - 1) * $limit;

        $query->limit($limit + 1)->offset($offset);

        // Ordering
        $order = $request->get('order', 'recent');

        if(!in_array($order, ['recent', 'oldest'])) $order = 'recent';

        if($order == 'recent') $query->orderBy('created_at', 'desc');
        else $query->orderBy('created_at', 'asc');

        // Fetch
        $ads = $query->with(['category', 'invoice'])
            ->withCount(['slots'])
            ->get()
            ->each(function($ad){
                $this->modifyResult($ad);
            });

        // Prepare result set
        $next = null;
        if(count($ads) > $limit){
            unset($ads[$limit]);
            $next = $page + 1;
        }

        $total_pages = ceil($total/$limit);

        return [
            'adverts' => $ads,
            'total' => $total,
            'pages' => $total_pages,
            'current_page' => $page,
            'next_page' => $next,
            'prev_page' => $page > 1 ? ($page - 1):null,
        ];
    }

    /**
     * Modify a fetched ad
     */
    function modifyResult($ad){
        if($ad != null){
            // Date
            $time = Carbon::createFromTimeString($ad->created_at);
            if($time->isToday()){
                $ad->time = "Today";
            }else if($time->isYesterday()){
                $ad->time = "Yesterday";
            }else{
                $ad->time = substr($time->monthName, 0, 3)." ".$time->day.", ".$time->year;
            }

            $ad->time .= ' '.($time->hour > 12 ? ($time->hour - 12):$time->hour).':'.($time->minute < 10 ? '0':'').$time->minute.' '.(($time->hour > 12) ? 'PM': ($time->hour == 12 ? 'Noon':'AM'));

            // Token
            $payment_service = resolve(\App\Services\PaymentService::class);
            $ad->token = $payment_service->getToken($ad);

            //
            $ad->category_name = $ad->category->name;

            return $ad;
        }
    }

    function getSummary(){

        return [
            'approved' => auth()->user()->adverts()->where('status', Advert::STATUS_APPROVED)->count(),
            'declined' => auth()->user()->adverts()->where('status', Advert::STATUS_DECLINED)->count(),
            'pending' => auth()->user()->adverts()->whereIn('status', [Advert::STATUS_PENDING_APPROVAL, Advert::STATUS_PENDING_REAPPROVAL])->count(),
        ];
    }
}
