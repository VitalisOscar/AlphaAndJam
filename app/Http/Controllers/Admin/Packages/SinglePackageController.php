<?php

namespace App\Http\Controllers\Admin\Packages;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\ScreenPrice;
use App\Models\StaffLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SinglePackageController extends Controller
{
    function view($id){
        $package = Package::where('id', $id)
            ->with(['priced_screens'])
            ->first();

        $priced = $package->priced_screens;
        $unpriced = $package->unpriced_screens;

        // Merge all screens, make price accessible from same attribute
        $screens = [];
        foreach($priced as $screen){
            $screen->price = $screen->pivot->price;
            array_push($screens, $screen);
        }

        foreach($unpriced as $screen){
            $screen->price = null;
            array_push($screens, $screen);
        }

        return response()->view('admin.packages.single', [
            'package' => $package,
            'screens' => $screens
        ]);
    }

    function edit(Request $request, $id){
        $validator = Validator::make($request->post(), [
            'name' => ['required'],
            'type' => ['required', 'in:peak,off_peak'],
            'clients' => ['required', 'integer', 'min:1'],
            'loops' => ['required', 'integer', 'min:1'],
            'from' => ['required', 'integer', 'min:0', 'max:23'],
            'to' => ['required', 'integer', 'min:0', 'max:23'],
        ],[
            'category.in' => 'Select a valid category',
            'clients.min' => 'Minimum number of clients is 1',
            'loops.min' => 'Minimum number of loops is 1',
            'from.min' => 'Select a valid start time',
            'to.min' => 'Select a valid end time',
            'from.max' => 'Select a valid start time',
            'to.max' => 'Select a valid end time',
        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors(['status' => $validator->errors()->first()]);
        }

        $package = Package::where('id', $id)
            ->first();

        $package->name = $request->post('name');
        $package->type = $request->post('type');
        $package->clients = $request->post('clients');
        $package->loops = $request->post('loops');
        $package->plays_from = $request->post('from');
        $package->plays_to = $request->post('to');

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Updated package '".$package->name."'",
            'item' => StaffLog::ITEM_PACKAGE,
            'item_id' => $package->id
        ]);

        DB::beginTransaction();

        if($package->save() && $log->save()){
            DB::commit();
            return back()->withInput()->with(['status' => "The package ".$package->name." has been updated"]);
        }

        DB::rollback();
        return back()->withInput()->withErrors(['status' => 'Something went unexpectedly wrong']);
    }

    function pricing(Request $request, $id){
        // Validate
        $validator = Validator::make($request->post(), [
            'prices' => ['required', 'array'],
            'prices.*' => ['array'],
            'prices.*.screen_id' => ['required', 'exists:screens,id'],
            'prices.*.price' => ['nullable', 'numeric', 'min:0']
        ], [
            'prices.required' => 'Please submit the different prices for the package',
            'prices.array' => 'Please submit the different prices for the package',
            'prices.*.array' => 'Please submit the different prices for the package',
            'prices.*.screen_id.required' => 'Use the available form to submit the pricing data',
            'prices.*.screen_id.exists' => 'Use the available form to submit the pricing data',
            'prices.*.price.numeric' => 'All prices should be valid positive values',
            'prices.*.price.min' => 'All prices should be valid positive values',
        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors(['status' => $validator->errors()->first()]);
        }

        $package = Package::where('id', $id)
                ->first();

        DB::beginTransaction();

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Updated pricing for package, '".$package->name."'",
            'item' => StaffLog::ITEM_PACKAGE,
            'item_id' => $package->id
        ]);

        // Loop through data, saving price info
        foreach($request->post('prices') as $new_price){
            if(!isset($new_price['screen_id'], $new_price['price'])){
                continue;
            }

            $screen_id = $new_price['screen_id'];
            $screen_price = $new_price['price'];

            // Check if price exists
            $price = ScreenPrice::where([
                'screen_id' => $screen_id,
                'package_id' => $id
            ])->first();

            if($price == null){
                // does not exist
                $price = new ScreenPrice([
                    'screen_id' => $screen_id,
                    'package_id' => $id
                ]);
            }

            $price->price = $screen_price;

            try{
                $price->save();
            }catch(Exception $e){
                DB::rollback();
                return back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
            }
        }

        if(!$log->save()){
            DB::rollback();
            return back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
        }

        DB::commit();
        return back()->withInput()->with(['status' => 'Pricing information for package '.$package->name.' has been captured and saved']);
    }
}
