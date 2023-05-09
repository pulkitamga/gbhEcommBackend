<?php

namespace App\Http\Controllers\api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Orderitems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders=Order::all();
        return response()->json([
            'status'=>200,
            'orders'=>$orders,
        ]);
    }
    //To get latest items form database
    public function latestorder()
    {
        $orders=Order::latest()->take(10)->get();
        return response()->json([
            'status'=>200,
            'orders'=>$orders,
        ]);
    }
    public function view($id)
    {
        $orders=Order::find($id);
        if($orders)
        {
            return response()->json([
                'status'=>200,
                'orders'=>$orders,
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No order found',
            ]);
        }
    }
      public function statusUpdate(Request $request,$id)
    {
         $orders=Order::where('id',$id)->first();
         if($orders)
         {
            $orders->order_status=$request->input('order_status');
            $orders->save();
            return response()->json([
                'status'=>200,
                'message'=>'Status Updated Succesfully',
            ]);
         }
         else{
            return response()->json([
                'status'=>404,
                'message'=>'No id found',
            ]);
           }
        
    }
   public function show(Request $rq,$id)
   {
      
      $ordersitems=Orderitems::with('order')->where('order_id',$rq->id)->get();
     if($ordersitems)
     {
        return response()->json([
            'status'=>200,
           'ordersitems'=>$ordersitems,
      ]);
     }
   }
   public function vieworder()
    {
        if(auth('sanctum')->check())
        {
            $user_id=auth('sanctum')->user()->id;
            $ordersitems=Order::where('user_id',$user_id)->get();
            if($ordersitems)
            {
            return response()->json([
                'status'=>200,
                 'ordersitems'=>$ordersitems,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Login to view cart',
            ]);
        }
        }
        
       
    }
   
    public function viewUserorder($id)
    {
        $orders=Order::find($id);
        if($orders)
        {
            return response()->json([
                'status'=>200,
                'userorders'=>$orders,
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No order found',
            ]);
        }
    }
    public function userorder(Request $rq,$id)
   {
      
      $ordersitems=Orderitems::with('order')->where('order_id',$rq->id)->get();
     if($ordersitems)
     {
        return response()->json([
            'status'=>200,
           'ordersitems'=>$ordersitems,
      ]);
     }
   }
   public function update(Request $request,$id)
   {
     $orders=Order::find($id);
     if($orders)
     {
         $orders->order_status=$request->input('order_status');
         $orders->update();
         return response()->json([
            'status'=>200,
            'message'=>'Update sucessfully'
         ]);
     }
     else
     {
        return response()->json([
            'status'=>404,
            'message'=>'not Update sucessfully'
         ]);

     }
   }
  
    

}
