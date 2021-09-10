<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\Category;
use App\Repository\AdminAdvertRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdvertsController extends Controller
{
    function getAll(Request $request, AdminAdvertRepository $repository){
        $result = $repository->getAll();

        return response()->view('admin.adverts.list', [
            'result' => $result,
            'category' => $request->filled('category') ? Category::where('id', $request->get('category'))->first() : null
        ]);
    }

    function export(AdminAdvertRepository $repository){
        $filename = 'adverts_'.str_replace('-', '_', Carbon::now()->format('Y-m-d')).'.csv';

        return Excel::download(new ExcelExport($repository->getQuery(null)->with('slots'), Advert::exportHeaders()), $filename);
    }

    function getSingle(AdminAdvertRepository $repository, $id){
        $ad = $repository->getSingle($id);

        return response()->view('admin.adverts.single', [
            'advert' => $ad
        ]);
    }
}
