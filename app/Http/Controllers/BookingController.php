<?php

namespace App\Http\Controllers;
use App\Booking;
use App\Mail\BookingMailSent;
use App\BookingMail;
use App\Mail\BookingCancelMailSent;
use App\BookingCancel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
class BookingController extends Controller
{
    public function allbooking($id)
    {
        return Booking::where('user_id', $id)->with('cylinder','user')->get();

    }

    public function Storebooking(Request $request){
        $data = $request->all();
        $ob =[
            'user_id'=> Auth::user()->id ,
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'email'=>$data['email'],
            'address'=> $data['address'],
            'product_id'=> $data['product_id'],
            'num_of_item'=> $data['num_of_item'],
       
        ];
        $boooking= Booking::create($ob);
        return Booking::where('id', $boooking->id)->with('cylinder','user')->get();
        // return Booking::create($ob);
    }

    public function cancelItem(Request $request)
    {  
        $data = $request->all();
        $ob =[
            'status'=>'cancel',
        ];
        return Booking::where('id',$request->id)->update($ob);;
    }

    public function allbookingadmin(Request $request)
    {
        $status = json_decode($request->status);
        $key = $request->key;
       $total = $request->total;
        return Booking::whereIn('status',$status)->with('cylinder','user','bookingMail','bookingCancelMail')->paginate($total);

    }
    public function Bookingsearch(Request $request){
        $key = $request->key;
        $total = $request->total;
        return Booking::where('name','like',"%$key%")->paginate($total);
    }

    public function StoreBookingmail(Request $request){
        $data = $request->all();
        Mail::to($request->email)
            ->send(new BookingMailSent($data));
        // return response()->json([
        //     'msg' => "Sent Mail",
        // ],200);
        $ob =[
            'email'=>$data['email'],
            'booking_id'=>$data['id'],
            'status'=>'sent',
        ];
        
        return BookingMail::create($ob);
    }
    public function Bookingcancelmail(Request $request){
        $data = $request->all();
        Mail::to($request->email)
            ->send(new BookingCancelMailSent($data));
        // return response()->json([
        //     'msg' => "Sent Mail",
        // ],200);
        $ob =[
            'email'=>$data['email'],
            'booking_id'=>$data['id'],
            'status'=>'sent',
        ];
        
        return BookingCancel::create($ob);
    }


}
