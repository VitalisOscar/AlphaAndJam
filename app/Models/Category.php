<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    public $fillable = ['name', 'slug'];

    function adverts(){
        return $this->hasMany(Advert::class);
    }

    static function exportHeaders(){
        return [
            'Name', 'Total Ads'
        ];
    }

    function getExportDataAttribute(){
        $row = [
            $this->name,
            $this->adverts_count == 0 ? '0':$this->adverts_count
        ];

        return $row;
    }
}
