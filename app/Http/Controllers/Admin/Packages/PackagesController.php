<?php

namespace App\Http\Controllers\Admin\Packages;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\StaffLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackagesController extends Controller
{
    function getAll(){
        $packages = Package::all();

        return response()->view('admin.packages.list', [
            'packages' => $packages
        ]);
    }

    function add(Request $request){
        $validator = Validator::make($request->post(), [
            'name' => ['required'],
            'type' => ['required', 'in:peak,off_peak'],
            'clients' => ['required', 'integer', 'min:1'],
            'from' => ['required', 'integer', 'min:0', 'max:23'],
            'to' => ['required', 'integer', 'min:0', 'max:23'],
        ],[
            'category.in' => 'Select a valid category',
            'clients.min' => 'Minimum number of clients is 1',
            'from.min' => 'Select a valid start time',
            'to.min' => 'Select a valid end time',
            'from.max' => 'Select a valid start time',
            'to.max' => 'Select a valid end time',
        ]);

        if($validator->fails()){
            return $request->expectsJson() ?
                $this->json->error($validator->errors()->first()) :
                back()->withInput()->withErrors(['status' => $validator->errors()->first()]);
        }

        $package = new Package([
            'name' => $request->post('name'),
            'type' => $request->post('type'),
            'clients' => $request->post('clients'),
            'plays_from' => $request->post('from'),
            'plays_to' => $request->post('to')
        ]);

        DB::beginTransaction();

        if($package->save()){
            // Staff log
            $staff = auth('staff')->user();

            $log = new StaffLog([
                'staff_id' => $staff->id,
                'activity' => "Created a new package, '".$package->name."'",
                'item' => StaffLog::ITEM_PACKAGE,
                'item_id' => $package->id
            ]);

            if($log->save()){
                DB::commit();
                return $request->expectsJson() ?
                    $this->json->success("The package ".$package->name." has been added") :
                    back()->withInput()->with(['status' => "The package ".$package->name." has been added"]);
            }
        }

        DB::rollback();

        return $request->expectsJson() ?
            $this->json->error('Something went unexpectedly wrong') :
            back()->withInput()->withErrors(['status' => 'Something went unexpectedly wrong']);
    }
}
