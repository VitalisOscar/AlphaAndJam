<?php

namespace App\Models;

use App\Interfaces\AdvertInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advert extends Model implements AdvertInterface
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'user_id',
        'category_id',
        'description',
        'content',
        'status'
    ];

    public $hidden = [
        'deleted_at'
    ];

    public $casts = [
        'content' => 'array'
    ];

    protected $with = ['user'];

    function user(){
        return $this->belongsTo(User::class);
    }

    function category(){
        return $this->belongsTo(Category::class);
    }

    function slots(){
        return $this->hasMany(Slot::class);
    }

    function scheduled_slot(){
        $request = request();

        return $this->hasOne(Slot::class)->where([
            'screen_id' => $request->get('screen'),
            'package_id' => $request->get('package'),
            'play_date' => $request->get('date')
        ]);
    }

    function any_scheduled_slot(){
        return $this->hasOne(Slot::class)
            ->whereHas('advert', function($q){
                $q->where('status', self::STATUS_APPROVED)
                ->where(function($q){
                    $q->whereHas('invoice', function($q){
                        $q->whereHas('payment', function($q1){
                            $q1->where('status', Payment::STATUS_SUCCESSFUL);
                        });
                    })
                    ->orWhereHas('user', function($q){
                        $q->where('payment->post_pay', true);
                    });
                });
            })
            ->where([
                'play_date' => Carbon::today()->format('Y-m-d')
            ]);
    }

    function getPriceAttribute(){
        $p = 0;

        foreach($this->slots as $slot){
            $p += $slot->price;
        }

        return $p;
    }

    static function exportHeaders(){
        return [
            'Client', 'Description', 'Category', 'Time Submitted', 'Status', 'Total Slots', 'Booking Price'
        ];
    }

    function getExportDataAttribute(){
        $row = [
            $this->user->name,
            $this->description,
            $this->category->name,
            $this->created_at,
            ($this->status == self::STATUS_APPROVED ? 'Approved':($this->status == self::STATUS_DECLINED ? 'Rejected':'Pending Approval')),
            $this->slots_count,
            $this->price
        ];

        return $row;
    }

    function getSlotGroupsAttribute(){
        $slots = $this->slots;

        $discovered_groups = [];
        $slot_groups = [];

        foreach($slots as $slot){
            $group = 's'.$slot->screen->id.'_p'.$slot->package->id;

            if(!in_array($group, $discovered_groups)){
                $slot_group = new SlotGroup();
                $slot_group->screen = $slot->screen;
                $slot_group->package = $slot->package;

                array_push($discovered_groups, $group);
                array_push($slot_groups, $slot_group);
            }else{
                $slot_group = $slot_groups[array_search($group, $discovered_groups)];
            }

            $slot_group->price += $slot->price;
            array_push($slot_group->slots, $slot);
        }

        return $slot_groups;
    }

    function invoice(){
        return $this->hasOne(Invoice::class);
    }

    function payments(){
        return $this->hasManyThrough(MpesaPayment::class, Invoice::class);
    }

    function getContentAttribute(){
        $content = $this->content;

        if(!is_array($content)){
            $content = json_decode($content, true);
        }

        $content['text'] = null;
        $content['phone'] = null;
        $content['email'] = null;

        return $content;
    }

    function isApproved(){
        return $this->getAttribute('status') == self::STATUS_APPROVED;
    }

    function isRejected(){
        return $this->getAttribute('status') == self::STATUS_DECLINED;
    }

    function hasImage(){
        $content = $this->getAttribute('content');
        return $content['media_type'] == 'image';
    }

    function hasVideo(){
        $content = $this->getAttribute('content');
        return $content['media_type'] == 'video';
    }

    function hasNoMedia(){
        $content = $this->getAttribute('content');
        return !isset($content['media_type']);
    }
}
