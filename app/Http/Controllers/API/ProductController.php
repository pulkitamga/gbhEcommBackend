<?php

namespace App\Http\Controllers\API;

use App\Models\Color;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Cviebrock\EloquentSluggable\Services\SlugService;


class ProductController extends Controller
{
    public function create()
    {
        $colors=Color::where('status','0')->get();
    }
    public function index()
    {
        $products=Product::all();
        return response()->json([
            'status'=>200,
            'products'=>$products,
        ]);

    }
    
 public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'category_id'=>'required|max:191',
                'slug'=>'required|max:191',
                'name'=>'required|max:191',
                'brand'=>'required|max:20',
                'selling_price'=>'required|max:20',
                'original_price'=>'required|max:20',
                'qty'=>'required|max:4',
                'quantity'=>'required|max:4',
                'size'=>'required|max:4',
                //'image'=>'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        
        if($validator->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }
        else
        {
          
            $product = new Product;
            $product->category_id = $request->input('category_id');
            $product->slug=($request->name);
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->brand = $request->input('brand');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->qty = $request->input('qty');
            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() .'.'.$extension;
                $file->move('uploads/product/', $filename);
                $product->image = 'uploads/product/'.$filename;
            }
            $product->status = $request->input('status') == true ? '1':'0';
            $product->save();
           
           
        //  {$variation=new Variation;
           
        //    foreach($variation as $key)
        //    {
        //     $product->productColors()->create(
        //         [
                    
        //            $variation['product_id']=$product->id,
        //            $variation['color_id']=>$request->color,
                  
        //             'size' => $request->input('size'),
        //             //$variation->quantity=$request->input('quantity'),
        //             $product->quantity = $request->input('quantity'),
        //         ]
        //         );
        //   }}
           // $variation->save();
         /*Product Attr Start
          $variation=new Variation; 
          $variation =[];
          $colorAtt=$request->input('color');
          $sizeAtt=$request->input('size');
          $qtyAtt=$request->input('quantity');
          foreach($variation as $item)
          {
            
               $variation->productColors()->create([
                'product_id'=>$product->id,
                'quantity'=>$qtyAtt,
                'size'=>$sizeAtt,
                'color_id'=>$colorAtt,
            ]);
            
          }
           //Variation::insert($variation);
         /*Product Attr end*/ 
             $variation=new Variation;
             foreach($variation as $item)
             {
                $product->productColors()->create([
                    'product_id'=>$product->id,
                    'color_id'=>$request->color,
                   'quantity'=>$request->quantity,
                     'size'=>$request->size,
                ]);
                $variation->save();
             }
            
            return response()->json([
                'status'=>200,
                'message'=>'Product Added Successfully',
            ]);
        }
    } 

    public function edit($id)
  {
    $product=Product::find($id);
    if($product)
    {
        return response()->json([
            'status'=>200,
            'product'=>$product,
        ]);

    }
    else
    {
        return response()->json([
            'status'=>404,
            'message'=>'No product found',
        ]);
    }
}   
public function update(Request $request,$id)
{
        
    $validator = Validator::make($request->all(), [
        'category_id'=>'required|max:191',
        'slug'=>'required|max:191',
        'name'=>'required|max:191',
        
        'brand'=>'required|max:20',
        'selling_price'=>'required|max:20',
        'original_price'=>'required|max:20',
        'qty'=>'required|max:4',
       
    ]);


if($validator->fails())
{
    return response()->json([
        'status'=>422,
        'errors'=>$validator->messages(),
    ]);
}
else
{
    $product = Product::find($id);
    if($product)
    {
     $product->category_id = $request->input('category_id');
      $product->slug = $request->input('slug');
      $product->name=$request->input('name');
      $product->description = $request->input('description');
      $product->brand = $request->input('brand');
      $product->selling_price = $request->input('selling_price');
      $product->original_price = $request->input('original_price');
      $product->qty = $request->input('qty');

    if($request->hasFile('image'))
    {
        $path=$product->image;
        if(File::exists($path));
        {
            File::delete($path);
        }
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() .'.'.$extension;
        $file->move('uploads/product/', $filename);
        $product->image = 'uploads/product/'.$filename;
    }
    $product->status = $request->input('status') ;
    $product->update();

    return response()->json([
        'status'=>200,
        'message'=>'Product Updated Successfully',
    ]);
}
else
{
    return response()->json([
        'status'=>404,
        'message'=>'Product Not Found',
    ]);
    
}
}
}
     public function statusupdate($id)
{
   $getStatus=Product::select('status')->where('id',$id)->first();
   if($getStatus->status===1)
    {
        $status=0;
        return response()->json([
            'status'=>200,
            'message'=>'Status Changed',
        ]);
        
       
 }
    else
    {
        $status=1;
         return response()->json([
            'status'=>202,
            'message'=>'Status Changed',
        ]);
    }
    Product::where('id',$id)->update(['status'=>$status]);
    return $getStatus;
    
    
}

public function getSlug(Request $request)
{
   $slug=SlugService::createSlug(Product::class,'slug',$request->name);
   return response()->json([
      'status'=>200,
      'slug'=>$slug,
   ]);
}
 
    

}
