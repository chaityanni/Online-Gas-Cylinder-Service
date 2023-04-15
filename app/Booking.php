<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'name','phone','address','product_id','user_id','num_of_item','email','status'
    ];
    public function cylinder(){
        return $this->belongsTo('App\Cylinder','product_id');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function bookingMail(){
        return $this->hasMany('App\BookingMail');
    }
    public function bookingCancelMail(){
        return $this->hasMany('App\BookingCancel');
    }
}
