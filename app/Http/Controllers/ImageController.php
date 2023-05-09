<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::latest()->get();
        return Inertia::render('ImageUpload', compact('images '));
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required'],
            'image' => ['required'],
        ])->validate();
  
        $image_name = time().'.'.$request->image->extension();  
        $request->file->move(public_path('uploads'), $image_name);
    
        Image::create([
            'title' => $request->title,
            'name' => $image_name
        ]);
    
        return redirect()->route('image.upload');
    }
}
