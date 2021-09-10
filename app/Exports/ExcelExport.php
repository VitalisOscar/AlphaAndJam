<?php

namespace App\Exports;

use App\Models\Advert;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromArray;

class ExcelExport implements FromArray
{
    private $query;
    private $headers;

    /**
     * @param Builder $query
     * @param array $headers
     */
    function __construct($query, $headers){
        $this->query = $query;
        $this->headers = $headers;
    }

    /**
    * @return array
    */
    public function array():array
    {
        $data = [];

        array_push($data, $this->headers);

        $items = $this->query->get();

        foreach($items as $item){
            array_push($data, $item->export_data);
        }

        return $data;
    }
}
