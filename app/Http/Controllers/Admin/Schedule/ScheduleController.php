<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Helpers\ResultSet;
use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PlaybackComment;
use App\Models\Screen;
use App\Models\Slot;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    function __invoke(Request $request)
    {
        // if($re)
        if(!($request->filled('screen') && $request->filled('package') && $request->filled('date'))){
            return response()->view('admin.schedule.schedule', [
                'adverts' => [],
                'fetched' => false
            ]);
        }

        $screen = Screen::where('id', $request->post('screen'))->first();
        $package = Package::where('id', $request->post('package'))->first();
        $date = $request->post('date');

        // Ad must be approved
        $query = Advert::where('status', Advert::STATUS_APPROVED)
            // Ad has a scheduled slot
            ->whereHas('scheduled_slot')
            // Invoice has been  paid for or client is post pay
            ->where(function($q){
                $q->whereHas('invoice', function($q){
                        $q->whereHas('payment', function($q1){
                            $q1->where('status', Payment::STATUS_SUCCESSFUL);
                        });
                    })
                    ->orWhereHas('user', function($q){
                        $q->where('payment->post_pay', true);
                    });
            })
            ->with(['user', 'scheduled_slot']);

        $playback_comment = PlaybackComment::where([
                'screen_id' => $request->post('screen'),
                'package_id' => $request->post('package'),
                'play_date' => $date,
            ])->first();

        return response()->view('admin.schedule.schedule', [
            'adverts' => $query->get(),
            'fetched' => true,
            'screen' => $screen,
            'package' => $package,
            'date' => $date,
            'playback_comment' => $playback_comment
        ]);
    }
}
