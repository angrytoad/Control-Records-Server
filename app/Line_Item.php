<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Line_Item extends Model
{
    use Eloquence;


    protected $table = 'line_items';
    public $incrementing = false;

    public function item_type()
    {
        return $this->hasOne('App\Item_Type');
    }

}
