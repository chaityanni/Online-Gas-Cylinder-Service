<?php

namespace App\Http\Controllers;
use App\Cylinder;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CylinderController extends Controller
{
    public function allCylinder(){
        return Cylinder::all();
    }

    public function relatedCylinder(Request $request){
        $id = $request->id;
        if($id){
            $product = Cylinder::where('id', $id)->first();
            $data=Cylinder::whereNotIn('id', [$id]);
        }       
         return $data->get();
    }

    public function show_cylinder($id){
        return Cylinder::where('id',$id)->get();
    }

    public function storeCylinder(Request $request){
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'price' => 'required|interger',
            'valve_type' => 'required|string|max:255',
            'valve_size' => 'required|interger',
            'valve_weight' => 'required|interger',
            'usage' => 'required|string|max:255',
            'product_type' => 'required|string|max:255',
            'surface_mat' => 'required|string|max:255',
            'cyl_diamater' => 'required|interger',
        ]);
        return Cylinder::create($data);
    }

    public function upload(Request $request)
    {
       \Log::info($request->all());
        
        request()->file('image')->store('uploads');
        $pic= $request->image->hashName();
        $pic= "/uploads/$pic";
        /*update the profile pic*/
        //return Gallery::create($data);
        return response()->json([
            'imageUrl'=> $pic
        ],200);
    }

    public function removeImage(Request $request){
        $data = $request->all();
        //return unlink(realpath($data['file']));
        $image_path = public_path().$data['file'];
        \Log::info(file_exists($image_path));
        if(unlink(($image_path))) return 1;
        else return 0;

        
    }
    public function removefile(Request $request){
        $data = $request->all();
        //return unlink(realpath($data['file']));
        $image_path = $data['file'];
        if(unlink(($image_path))) return 1;
        else return 0;

        
    }
    public function search(Request $request){
        $key = $request->key;
        $total = $request->total;
        return Cylinder::where('title','like',"%$key%")->paginate($total);
    }

    public function edit_item(Request $request){
        $data = $request->all();
        \Log::info($data);
        return Cylinder::where('id',$data['id'])->update($data);
    }

    public function editStatus(Request $request){
        $data = $request->all();
        \Log::info($data);
        return Cylinder::where('id',$data['id'])->update($data);
    }
    public function editStatusNew(Request $request){
        $status = json_decode($request->status);
         $key = $request->key;
        $total = $request->total;
        return Cylinder::whereIn('status',$status)->where('title','like',"%$key%")->paginate($total);
    }

    public function delete_item(Request $request){
        $data = $request->all();
        return Cylinder::where('id',$data['id'])->delete();
    }

}
