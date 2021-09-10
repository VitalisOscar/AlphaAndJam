<?php

namespace App\Http\Controllers\Admin\Screens;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Models\StaffLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScreensController extends Controller
{
    function getAll(Request $request){
        $screens = Screen::all();

        return response()->view('admin.screens.list', [
            'screens' => $screens
        ]);
    }

    function add(Request $request){
        $validator = validator()->make($request->post(), [
            'title' => ['required'],
            // 'slug' => ['unique:screens,slug'],
        ],[
            'title.required' => 'Provide the screen title, e.g Kimathi Street',
            'slug.unique' => 'The screen '.$request->post('title').' already exists'
        ]);

        if($validator->fails()){
            return $request->expectsJson() ?
                $this->json->error($validator->errors()->first()) :
                back()->withInput()->withErrors(['status' => $validator->errors()->first()]);
        }

        // admin log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Added a new screen, '".$request->post('title')."'",
            'item' => StaffLog::ITEM_SCREEN
        ]);


        $slug = preg_replace("/ +/", '-', strtolower($request->post('title')));

        // Check if there is a soft deleted screen and restore it
        $screen = Screen::where('slug', $slug)->withTrashed()->first();

        if($screen != null){
            if($screen->trashed()){
                $log->item_id = $screen->id;
                $log->activity = "Restored previously deleted screen, '".$screen->title."'";

                $screen->restore();
                $log->save();

                return $request->expectsJson() ?
                    $this->json->success("The screen ".$screen->title." has been restored") :
                    back()->withInput()->with(['status' => "The screen ".$screen->title." has been restored"]);
            }

            return $request->expectsJson() ?
                $this->json->success("The screen ".$screen->title." already exists") :
                back()->withInput()->withErrors(['status' => "The screen ".$screen->title." already exists"]);
        }

        $screen = new Screen([
            'title' => $request->post('title'),
            'online' => $request->boolean('online'),
            'slug' => $slug,
        ]);

        DB::beginTransaction();

        try{
            $screen->save();
            
            $log->item_id = $screen->id;
            $log->save();

            DB::commit();

            return $request->expectsJson() ?
                $this->json->success("New screen ".$screen->title." has been saved") :
                back()->withInput()->with(['status' => "New screen ".$screen->title." has been saved"]);

        }catch(Exception $e){
            return $request->expectsJson() ?
                $this->json->error('Something went unexpectedly wrong') :
                back()->withInput()->withErrors(['status' => 'Something went unexpectedly wrong']);
        }
    }

}
