<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Mail\Passwordreset;
use Illuminate\Support\Facades\Mail;
use App\Mail\resetpasswordMaillabel;
use App\ResetPassWord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function registration(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
         User::create($data);
		return redirect("/login");
    }
    public function login(Request $request){
        
        if((User::where('email', $request->email)->count())==0){
            return response()->json([
                'msg' => "Email doesn't exist!",
            ],422);
        }

         if((User::where('email', $request->email)->count())==0){
            return redirect("/login")->with('message',"Incorrect Email!");
        }
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password ])) {
           $user = Auth::user();
           if($user->user_type == 'User'){
            return redirect("/");
                // $url= URL::previous();
                //     return redirect($url);
            }
            if($user->user_type == 'Admin'){
                return redirect("/admin");
             }
             if($user->user_type == 'venueOwner'){
                return redirect("/");
             }
         }
         else{
            return  redirect("/login")->with('message',"Incorrect Password!");
         }

    }

     // Password Reset

     public function passwordresetGetEmail(Request $request){
        $isFound = User::where('email',$request->email)->count();

        if($isFound==0){
            return response()->json([
                'msg' => "Email doesn't exist!",
            ],401);
        }
            // $token=str_random(30);
            $token=  Str::random(40);
            // $token=  Str::random(30);
           
            \Log::info($token);
            \DB::table('password_resets')->where('email',$request->email)->delete();
            $savedData = \DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token, //change 60 to any length you want
                'created_at' => \Carbon\Carbon::now()
            ]);
                $data = [
                    'token' => $token
                ];
                 \Log::info($data);
                Mail::to($request->email)
                ->send(new Passwordreset($data));
              return response()->json([
                'msg' => "password reset link has been Sent!",
            ],200);
    }

    public function matchPasswordLink(Request $request){
        $token = $request->token;
      
        // get the row from reset table matching this token  http://laravel-vue-authentication.test/passwordreset/$2y$10$D8PB0yYEkbapLjjyFOgozus3BG3.RpBNDgWJJ/hPjFFu9zKDqrQPO
        // get the row from reset table matching this token  http://127.0.0.1:8000/passwordreset?token=$2y$10$OZNTE.VMrt5tCpa4RmxTDenPGL0AiVAn4YCs0GMKzEroasgv1HAaS 

        $isTokenFound = \DB::table('password_resets')->where('token',$token)->first();
   
        
        if(!$isTokenFound){
            return response()->json([
                'msg' => "token doesn't exist!",
            ],401);
        }
        return response()->json([
            'data' => $isTokenFound,
        ],200);
    }
    public function resetPassword(Request $request){
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',
        ]);

        $flag = User::where("email",$request->email)->update(['password' => Hash::make($request->password)]);
        if(!$flag){
            return response()->json([
                'msg' => "Email doesn't exist!",
            ],401);
        }

        \DB::table('password_resets')->where('email',$request->email)->delete();
        return $flag;

    }


    public function updateUser(Request $request){
        $data = $request->all();
        \Log::info($data);
        return User::where('id',$data['id'])->update($data);
    }

    public function deleteUser(Request $request){
        $data = $request->all();
        return User::where('id',$data['id'])->delete();
    }
    public function all_user_pagi(Request $request){
        $total = $request->total;
        $data = User::orderBy('id');
        return $data->paginate($total);
    }

    public function changePassword(Request $request){
        $data = $request->all();
        $user = Auth::user();
       
        if(!Hash::check($request->current_password, $user->password)){
            return response()->json([
                'msg' => 'Old password is not correct.',
                'success' => false
            ],401);
        }

        $this->validate($request,[
        
            'new_password'=>'required|string|min:6'
        ]
        );

        $password = Hash::make($data['new_password']);
    
        $user = User::where('id', $user->id)->update(['password' => $password]);
        return response()->json([
        'user' => $user,
        'success' => true
        ],200);
    }
}
