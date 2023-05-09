<?php

namespace App\Http\Controllers\api;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    //
    
    public function addtocart(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;

            $productCheck = Product::where('id',$product_id)->first();
            if($productCheck)
            {
                if(Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists())
                {
                    return response()->json([
                        'status'=> 409,
                        'message'=>$productCheck->name.' Already Added to Cart',
                    ]);
                }
                else
                {
                    $cartitem = new Cart;
                    $cartitem->user_id = $user_id;
                    $cartitem->product_id = $product_id;
                    $cartitem->product_qty = $product_qty;
                    $cartitem->save();

                    return response()->json([
                        'status'=> 201,
                        'message'=> 'Added to Cart',
                    ]);
                }
            }
            else
            {
                return response()->json([
                    'status'=> 404,
                    'message'=> 'Product Not Found',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=> 401,
                'message'=> 'Login to Add to Cart',
            ]);
        }
    }
    public function viewcart()
    {
        if(auth('sanctum')->check())
        {
            $user_id=auth('sanctum')->user()->id;
            $cartitem=Cart::where('user_id',$user_id)->get();
            return response()->json([
                'status'=>200,
                 'cart'=>$cartitem,
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
    public function updatequantity($cart_id,$scope)
    {
      if(auth('sanctum')->check())
      {
        $user_id=auth('sanctum')->user()->id;
        $cartitem=Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
        if($scope=="inc")
        {
            $cartitem->product_qty +=1;
        }
        else if($scope=="dec")
        {
            $cartitem->product_qty -=1;
        }
        $cartitem->update();
        return response()->json([
            'status'=>200,
            'message'=>'Quantity Updated'
        ]);

      }
      else{
        return response()->json([
            'status'=>401,
            'message'=>'Login In TO continue',
        ]);
      }
    }
    public function deletecartitem($cart_id)
    {
        if(auth('sanctum')->check())
        {
            $user_id=auth('sanctum')->user()->id;
            $cartitem=Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($cartitem)
            {
                $cartitem->delete();
                return response()->json([
                    'status'=>200,
                    'mesaage'=>"Removed Sucessfully",
                ]);

            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>"Cart Item Not Found",
                ]);

            }

        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>'Login To Continue'
            ]);
        }

    }
}
