<?php

namespace App\Http\Controllers\api;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),
        [
            'name'=>'required|max:191',
            'slug'=>'required|max:191',
            
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        else
        {
               $brand=new Brand;
               $brand->name=$request->input('name');
               $brand->slug=$request->input('slug');
               $brand->status=$request->input('status')==true?'1':'0';
               $brand->save();
               return response()->json([
                'status'=>200,
                'message'=>'Brand Added Sucessfully',
               ]);
        }
    }
    public function index()
    {
        $brand=Brand::all();
       return response()->json([
        'status'=>200,
        'brand'=>$brand,
       ]);
    }
}
