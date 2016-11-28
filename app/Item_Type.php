<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Item_Type extends Model
{
    use Eloquence;


    protected $table = 'item_types';
    public $incrementing = false;

}
