<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingMail extends Model
{
    protected $fillable = [
        'email','booking_id','status'
     ];

     public function booking(){
        return $this->belongsTo('App\Booking','booking_id');
    }
}
