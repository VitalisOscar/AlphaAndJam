<?php

namespace App\Http\Controllers\Admin\Clients;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    function getAll(Request $request){
        $query = User::client();

        // search
        if($request->filled('search')){
            $search = $request->get('search');
            $query->where(function($q) use($search){
                $q->where('business->phone', 'like', '%'.$search.'%')
                    ->orWhere('business->email', 'like', '%'.$search.'%')
                    ->orWhere('business->name', 'like', '%'.$search.'%');
            });
        }

        // Verification Status
        if($request->filled('status')){
            $status = $request->get('status');

            if($status == 'verified') $query->approved();
            else if($status == 'pending') $query->pending();
            else if($status == 'rejected') $query->rejected();
        }

        // Sort
        $order = intval($request->get('order'));
        if($order == 1) $query->orderBy('registered_at', 'asc');
        else if($order == 2) $query->orderBy('business->name', 'asc');
        else if($order == 3) $query->orderBy('business->name', 'desc');
        else $query->orderBy('registered_at', 'desc');

        $clients = $query->withCount('adverts')->get()->each(function($client){
            $time = Carbon::createFromTimeString($client->registered_at);

            if($time->isToday()){
                $client->date = "Today";
            }else if($time->isYesterday()){
                $client->date = "Yesterday";
            }else{
                $client->date = substr($time->monthName, 0, 3)." ".($time->day < 10 ? '0':'').$time->day.", ".$time->year;
            }
        });

        return response()->view('admin.clients.list', [
            'clients' => $clients,
            'prev_page' => 2,
            'current_page' => 3,
            'next_page' => 4,
            'pages' => 6
        ]);
    }
}
