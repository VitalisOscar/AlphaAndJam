<?php

namespace App\Http\Controllers\Admin\Screens;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Models\ScreenPrice;
use App\Models\StaffLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SingleScreenController extends Controller
{
    function getSCreen(Request $request, $slug){
        $screen = Screen::where('slug', $slug)->first();

        $priced = $screen->priced_packages;
        $unpriced = $screen->unpriced_packages;

        // Merge all screens, make price accessible from same attribute
        $packages = [];
        foreach($priced as $package){
            $package->price = $package->pivot->price;
            array_push($packages, $package);
        }

        foreach($unpriced as $package){
            $package->price = null;
            array_push($packages, $package);
        }

        return response()->view('admin.screens.single', [
            'packages' => $packages,
            'screen' => $screen
        ]);
    }

    function pricing(Request $request, $slug){
        // Validate
        $validator = Validator::make($request->post(), [
            'prices' => ['required', 'array'],
            'prices.*' => ['array'],
            'prices.*.package_id' => ['required', 'exists:packages,id'],
            'prices.*.price' => ['nullable', 'numeric', 'min:0']
        ], [
            'prices.required' => 'Please submit the different prices for the package',
            'prices.array' => 'Please submit the different prices for the package',
            'prices.*.array' => 'Please submit the different prices for the package',
            'prices.*.package_id.required' => 'Use the available form to submit the pricing data',
            'prices.*.package_id.exists' => 'Use the available form to submit the pricing data',
            'prices.*.price.numeric' => 'All prices should be valid positive values',
            'prices.*.price.min' => 'All prices should be valid positive values',
        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors(['status' => $validator->errors()->first()]);
        }

        $screen = Screen::where('slug', $slug)
                ->first();

        DB::beginTransaction();

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Updated pricing for screen, '".$screen->title."'",
            'item' => StaffLog::ITEM_SCREEN,
            'item_id' => $screen->id
        ]);

        // Loop through data, saving price info
        foreach($request->post('prices') as $new_price){
            if(!isset($new_price['package_id'], $new_price['price'])){
                continue;
            }

            $package_id = $new_price['package_id'];
            $package_price = $new_price['price'];

            // Check if price exists
            $price = ScreenPrice::where([
                'screen_id' => $screen->id,
                'package_id' => $package_id
            ])->first();

            if($price == null){
                // does not exist
                $price = new ScreenPrice([
                    'screen_id' => $screen->id,
                    'package_id' => $package_id
                ]);
            }

            $price->price = $package_price;

            try{
                $price->save();
            }catch(Exception $e){
                DB::rollback();
                return back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
            }
        }

        try{
            if(!$log->save()){
                DB::rollback();
                return back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
            }
        }catch(Exception $e){
            DB::rollback();
            return back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
        }

        return back()->withInput()->with(['status' => 'Pricing information for screen '.$screen->title.' has been captured and saved']);
    }

    function delete(Request $request, $slug){
        $screen = Screen::where('slug', $slug)->first();

        if($screen == null){
            return $request->expectsJson() ?
                $this->json->error('Screen was not found in database') :
                back()->withInput()->withErrors(['status' => 'Screen was not found in database']);
        }

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Deleted the screen '".$screen->title."'",
            'item' => StaffLog::ITEM_SCREEN,
            'item_id' => $screen->id
        ]);

        DB::beginTransaction();
        if($screen->delete() && $log->save()){
            DB::commit();
            return $request->expectsJson() ?
                $this->json->success('The screen '.$screen->title.' has been deleted from screens') :
                redirect()->route('admin.screens')->withInput()->with(['status' => 'The screen '.$screen->title.' has been deleted from screens']);
        }

        DB::rollback();
        return $request->expectsJson() ?
            $this->json->error('Unable to delete screen, something went wrong. Please retry') :
            back()->withInput()->withErrors(['status' => 'Unable to delete screen, something went wrong. Please retry']);
    }

    function edit(Request $request, $slug){
        $screen = Screen::where('slug', $slug)->first();

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

        $slug = preg_replace("/ +/", '-', strtolower($request->post('title')));


        if($screen == null){
            return $request->expectsJson() ?
                $this->json->error('Screen was not found in database') :
                back()->withInput()->withErrors(['status' => 'Screen was not found in database']);
        }

        // Make change
        $screen->title = $request->post('title');
        $screen->online = $request->boolean('online');
        $screen->slug = $slug;

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Updated screen '".$screen->title."'",
            'item' => StaffLog::ITEM_SCREEN,
            'item_id' => $screen->id
        ]);

        DB::beginTransaction();

        if($screen->save() && $log->save()){
            DB::commit();
            return $request->expectsJson() ?
                $this->json->success('Screen '.$screen->title.' has been updated') :
                redirect()->route('admin.screens.single', $screen->slug)->withInput()->with(['status' => 'Screen '.$screen->title.' has been updated']);
        }

        DB::rollback();
        return $request->expectsJson() ?
            $this->json->error('Unable to make change, something went wrong. Please retry') :
            back()->withInput()->withErrors(['status' => 'Unable to make change, something went wrong. Please retry']);

    }
}
