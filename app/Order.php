<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Order extends Model
{
    use Eloquence;
    

    protected $table = 'orders';
    public $incrementing = false;
    
}
