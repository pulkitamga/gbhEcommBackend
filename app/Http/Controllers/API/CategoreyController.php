<?php

namespace App\Http\Controllers\API;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoreyController extends Controller
{
    public function index()
    {
        $category=Category::all();
        return response()->json([
            'status'=>200,
            'category'=>$category,

        ]);
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),
        [
             'slug'=>'required|max:191',
             'name'=>'required|max:191',
             'image'=>'required|image|mimes:jpeg,png,jpg|max:2048',


        ]);
        if($validator->fails())
        {
            return response()->json(
                [
                    'status'=>400,
                    'errors'=>$validator->messages(),
                ]
                );
        }
        else
        {
            $category=new Category;
             //first database filed name then input field name
            $category->slug=$request->input('slug');
            $category->name=$request->input('name');
            $category->parent_id=$request->input('category_id');
            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() .'.'.$extension;
                $file->move('uploads/category/', $filename);
                $category->image = 'uploads/category/'.$filename;
            }
            $category->description=$request->input('description');
            $category->status=$request->input('status')==true ? '1':'0';
            $category->save();
            return response()->json([
                'status'=>200,
                'message'=>'Category Added Succesfully',
            ]);
    }
    }

    public function edit($id)
    {
        $category=Category::find($id);
        if($category)
        {
            return response()->json([
                'status'=>200,
                'category'=>$category
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No category Id Found'
            ]);
        }
    }
    public function destroy($id)
    {
        $category=Category::find($id);
        if($category)
        {
            $category->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Category Deleted Successfully',
            ]);
            
        }
        else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>'No Category Id Found',
                ]);
            }

    }
    public function update(Request $request,$id)
    {
        $validator=Validator::make($request->all(),
        [
             'slug'=>'required|max:191',
             'name'=>'required|max:191',

        ]);
        if($validator->fails())
        {
            return response()->json(
                [
                    'status'=>422,
                    'errors'=>$validator->messages(),
                ]
                );
        }
        else
        {
            $category=Category::find($id);
            if($category)
            {
             //first database field name then input field name
            $category->slug=$request->input('slug');
            $category->name=$request->input('name');
            $category->description=$request->input('description');
            $category->status=$request->input('status')==true ? '1':'0';
            $category->save();
            return response()->json([
                'status'=>200,
                'message'=>'Category Updated Succesfully',
            ]);
           }
           else{
            return response()->json([
                'status'=>404,
                'message'=>'No category id found',
            ]);
           }
    }

    }
    public function allcategory()
    {
        $category=Category::where('status','0')
        ->whereNull('parent_id')->get();
        return response()->json([
            'status'=>200,
            'category'=>$category,

        ]);
    }
}
