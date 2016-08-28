<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Band_Additional extends Model
{
    protected $table = 'bands_additional';
    protected $fillable = [
        'id',
        'band_id',
        'band_banner_id',
        'band_avatar_id'
    ];

    public function band()
    {
        return $this->belongsTo('App\Band');
    }
}
