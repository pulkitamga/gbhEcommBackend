<?php

namespace App\Http\Controllers\api;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class ColorController extends Controller
{
    public function index()
    {
        $color=Color::all();
        return response()->json([
            'status'=>200,
            'color'=>$color,
        ]);
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),
        [
            'color'=>'required|max:191',
            'color_code'=>'required|max:191',

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
            $color=new Color;
            $color->color=$request->input('color');
            $color->color_code=$request->input('color_code');
            $color->status=$request->input('status')==true?'1':'0';
            $color->save();
            return response()->json([
                'status'=>200,
                'message'=>"Color added sucessfully",
            ]);


        }
        
    }
}
