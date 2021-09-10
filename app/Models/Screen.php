<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screen extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    public $fillable = ['title', 'online', 'slug'];

    function slots(){
        return $this->hasMany(Slot::class);
    }

    function packages(){
        return $this->belongsToMany(Package::class, 'screen_prices')->withPivot('price');
    }

    function getUnpricedPackagesAttribute(){
        return Package::query()->whereDoesntHave('priced_screens', function($q){
            $q->where('screen_id', $this->getAttribute('id'));
        })->get();
    }

    function priced_packages(){
        return $this->belongsToMany(Package::class, 'screen_prices')->withPivot('price');
    }
}
