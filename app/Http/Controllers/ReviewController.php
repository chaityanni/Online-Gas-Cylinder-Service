<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function insertreview(Request $request)
    {
       $data = $request->all();
       if(!Auth::check()){
        return response()->json([
                'message' => "You are not Authenticate User!",
            ], 401);
        }
        $ob =[
                 'user_id'=> Auth::user()->id ,
                 'product_id'=>$data['product_id'],
                 'rating'=> $data['rating'],
                 'content'=> $data['content'],
             ];
        $return= Review::create($ob);
        if($return) return Review::where('id',$return->id)->with('user','cylinder')->first();
    }
    // public function showreview(Request $request)
    // {
    //     $total = $request->total;
    //     return Review::with('user','add_venue')->paginate($total);
    //     // return $return;
    // }
    public function UserRatingShow($id)
    {
        return Review::where('user_id',$id)->with('user','cylinder')->get();
    }
}
