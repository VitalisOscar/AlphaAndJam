<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PlaybackComment;
use App\Models\Screen;
use Illuminate\Http\Request;

class PlaybackCommentsController extends Controller
{
    function save(Request $request)
    {
        $screen = Screen::where('id', $request->post('screen'))->first();
        $package = Package::where('id', $request->post('package'))->first();
        $date = $request->post('date');
        $comment = $request->post('comment');

        if($comment == 'played') $comment = 'Played successfully with no errors';
        else if($comment == 'not_played') $comment = 'Not played at all';
        else if($comment == 'played_with_errors') $comment = 'Played but encountered errors';

        //
        if($screen == null || $package == null || $date == null){
            return back()->withErrors([
                'status' => 'Please use the provided form to save comment',
            ]);
        }

        // Check if comment exists


        $playback_comment = new PlaybackComment([
            'package_id' => $package->id,
            'screen_id' => $screen->id,
            'play_date' => $date,
            'comment' => $comment,
            'staff_id' => auth('staff')->id()
        ]);

        if($playback_comment->save()){
            return back()->with([
                'status' => 'Playback comment has been saved',
            ]);
        }

        return back()->withErrors([
            'status' => 'Something went wrong. Please try again',
        ]);
    }
}
