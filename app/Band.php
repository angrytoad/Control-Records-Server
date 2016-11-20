<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Band extends Model
{
    use Eloquence;

    protected $searchableColumns = ['name'];
    
    protected $table = 'bands';

    public function band_additional()
    {
        return $this->hasOne('App\Band_Additional');
    }

    public function store_config(){
        $this->belongsToMany('App\Store_Configuration');
    }

    public function songs(){
        $this->belongsToMany('App\Song', 'band_id', 'id');
    }
}
