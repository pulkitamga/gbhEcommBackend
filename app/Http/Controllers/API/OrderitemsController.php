<?php

namespace App\Http\Controllers\api;

use App\Models\Orderitems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderitemsController extends Controller
{
    /*public function view($id)
    {
        $ordersitems=Orderitems::find($id);
        if($ordersitems)
        {
            return response()->json([
                'status'=>200,
                'ordersitems'=>$ordersitems,
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No order found',
            ]);
        }
    }*/
    public function test(Request $request,$id)
    {
        $ordersitems=Orderitems::where($id)->get();
        return response()->json([
            'status'=>200,
            'ordersitems'=>$ordersitems,
        ]);
    }
   
}
