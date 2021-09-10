<?php

namespace App\Http\Controllers\Admin\Agents;

use App\Helpers\ResultSet;
use App\Http\Controllers\Controller;
use App\Models\StaffLog;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgentsController extends Controller
{
    function getAll(Request $request){
        $query = User::agent();

        // search
        if($request->filled('search')){
            $search = $request->get('search');
            $query->where(function($q) use($search){
                $q->where('user->phone', 'like', '%'.$search.'%')
                    ->orWhere('user->email', 'like', '%'.$search.'%')
                    ->orWhere('user->name', 'like', '%'.$search.'%');
            });
        }

        // Verification Status
        if($request->filled('status')){
            $status = $request->get('status');

            if($status == 'approved') $query->approved();
            else if($status == 'rejected') $query->rejected();
        }

        // Sort
        $order = intval($request->get('order'));
        if($order == 'past') $query->orderBy('registered_at', 'asc');
        else if($order == 'az') $query->orderBy('name', 'asc');
        else if($order == 'za') $query->orderBy('name', 'desc');
        else $query->orderBy('registered_at', 'desc');

        $query->withCount('adverts');



        return response()->view('admin.agents.list', [
            'result' => new ResultSet($query, 15)
        ]);
    }

    function add(Request $request){
        $validator = validator($request->post(), [
            'name' => 'required|string',
            'phone' => 'required|regex:/0([0-9]){9}/|unique:users,user->email',
            'email' => 'required|email|unique:users,user->phone',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }

        // Staff log
        $staff = auth('staff')->user();

        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Created new agent account for '".$request->post('name')."'",
            'item' => StaffLog::ITEM_AGENT
        ]);

        $agent = new User([
            'business' => [
                'name' => 'Agent'
            ],
            'type' => 'agent',
            'user' => [
                'name' => $request->post('name'),
                'phone' => $request->post('phone'),
                'email' => $request->post('email'),
            ],
            'password' => Hash::make($request->post('password')),
            'verification' => [
                'account' => Carbon::now()->toDateTimeString(),
                'business' => Carbon::now()->toDateTimeString(),
                'official_phone' => Carbon::now()->toDateTimeString(),
                'email' => Carbon::now()->toDateTimeString()
            ]
        ]);

        DB::beginTransaction();
        try{
            $agent->save();

            $log->item_id = $agent->id;
            $log->save();

            DB::commit();

            return $request->expectsJson() ?
                $this->json->success("New agent account for ".$agent->name." has been created") :
                    back()->with(['status' => "New agent account for ".$agent->name." has been created"]);

        }catch(Exception $e){
            DB::rollback();
            return $request->expectsJson() ?
                $this->json->error('Something went unexpectedly wrong') :
                back()->withInput()->withErrors(['status' => 'Something went unexpectedly wrong']);
        }
    }
}
