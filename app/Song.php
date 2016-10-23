<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $table = 'songs';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'song_name',
        'band_id',
        'full_song_id',
        'full_song_url',
        'sample_song_id',
        'sample_song_url',
        'public'
    ];

    /**
     * The roles that belong to the user.
     */
    public function albums()
    {
        return $this->belongsToMany('App\Album');
    }

    public function band()
    {
        return $this->belongsTo('App\Band');
    }
}
