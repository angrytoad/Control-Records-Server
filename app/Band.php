<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    protected $table = 'bands';

    public function band_additional()
    {
        return $this->hasOne('App\Band_Additional');
    }
}
