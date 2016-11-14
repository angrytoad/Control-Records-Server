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
}
