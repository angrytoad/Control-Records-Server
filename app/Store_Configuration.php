<?php
/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 19/11/2016
 * Time: 15:36
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store_Configuration extends Model
{
    protected $table = 'store_configurations';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'configuration_name',
        'configuration_active',
        'featured_article',
        'featured_album',
        'featured_artist'
    ];

    /**
     * The featured items as part of the store config
     */
    public function store_album()
    {
        return $this->hasOne('App\Album', 'id', 'featured_album');
    }

    public function store_article()
    {
        return $this->hasOne('App\News', 'id', 'featured_article');
    }

    public function store_artist(){
        return $this->hasOne('App\Band', 'id', 'featured_artist');
    }
}