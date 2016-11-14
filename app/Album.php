<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Album extends Model
{
    use Eloquence;

    protected $searchableColumns = ['album_name'];

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
