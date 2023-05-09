<?php

namespace App\Http\Controllers\api;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Mail\PlaceOrderMailable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    //
    public function placeorder(Request $request)
    {
        if(auth('sanctum')->check())
        {
          $validator=Validator::make($request->all(),[
            'firstname'=>'required|max:191',
            'lastname'=>'required|max:191',
            'phone'=>'required|max:191',
            'email'=>'required|max:191',
            'address'=>'required|max:191',
            'city'=>'required|max:191',
            'state'=>'required|max:191',
            'zipcode'=>'required|max:191',

          ]);
          if($validator->fails())
          {
            return response()->json([
                'status'=>422,
                'error'=>$validator->messages(),
            ]);
          }
          else
          {
            $user_id=auth('sanctum')->user()->id;
            $order=new Order;
            //1st db filed name
            $order->user_id=$user_id;
            $order->firstname=$request->firstname;
            $order->lastname=$request->lastname;
            $order->phone=$request->phone;
            $order->email=$request->email;
            $order->address=$request->address;
            $order->city=$request->city;
            $order->state=$request->state;
            $order->zipcode=$request->zipcode;
            $order->payment_mode=$request->payment_mode;
            $order->payment_id=$request->payment_id;
            $order->tracking_no='gbhecomm'.rand(1111,9999);
            $order->order_status='in progress';
            $order->save();

            $cart=Cart::where('user_id',$user_id)->get();
            $orderitems=[];
            foreach($cart as $item){
                $orderitems[]=[
                    'product_id'=>$item->product_id,
                    'qty'=>$item->product_qty,
                    'price'=>$item->product->selling_price,
                ];

                //decrement of product qty in product table
                $item->product->update([
                    'qty'=>$item->product->qty - $item->product_qty
                ]);

            }
             $order->orderitems()->createMany($orderitems);
             Cart::destroy($cart);

            if($order)
            {
                try
                {
                    //mail sent
                   
                    Mail::to('pulkitamga0610@gmail.com')
                    ->send(new PlaceOrderMailable($order));
                    return response()->json([
                        'status'=>200,
                        'message'=>'order Place Suessfully with mail', 
                    ]);
                }
                catch(\Exception $e)
                {
                    //mail not send
                    return response()->json([
                        'status'=>500,
                        'message'=>'sorry mail not send', 
                    ]);
                }
            
        }
          }
          
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>"Login to continue",
            ]);
        }

    }
    public function validateorder(Request $request)
    {
       
        if(auth('sanctum')->check())
        {
            $validator=Validator::make($request->all(),[
            'firstname'=>'required|max:191',
            'lastname'=>'required|max:191',
            'phone'=>'required|max:191',
            'email'=>'required|max:191',
            'address'=>'required|max:191',
            'city'=>'required|max:191',
            'state'=>'required|max:191',
            'zipcode'=>'required|max:191',
            ]);
            if($validator->fails())
            {
                return response()->json([
                    'status'=>422,
                    "errors"=>$validator->messages(),
                ]);
            }
            else
            {

                return response()->json([
                    'status'=>200,
                    "errors"=>'Succesfully',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Login to continue',
            ]);
        }
    }
}
