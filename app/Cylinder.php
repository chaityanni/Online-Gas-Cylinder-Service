<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cylinder extends Model
{
      protected $fillable = [
        'title','image','brand_name','price','valve_type','valve_size','valve_weight','usage','product_type','surface_mat','cyl_diamater','status'
    ];
    public $timestamps = false;
}
