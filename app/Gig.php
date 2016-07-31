<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    protected $table = 'gigs';

    public function venue()
    {
        return $this->belongsTo('App\Venue');
    }

    public function band()
    {
        return $this->belongsTo('App\Band');
    }
}
