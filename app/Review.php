<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable =[
        'rating','content','user_id','product_id'
    ]; 
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function cylinder(){
        return $this->belongsTo('App\Cylinder','product_id');
    }
}
