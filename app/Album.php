<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'albums';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'album_name',
        'album_image',
        'album_image_url',
        'public'
    ];


    /**
     * The roles that belong to the user.
     */
    public function songs()
    {
        return $this->belongsToMany('App\Song');
    }
}
