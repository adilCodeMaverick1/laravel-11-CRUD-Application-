<?php

namespace App\Http\Controllers;

use App\Models\blogs;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs =Blogs::orderBy("created_at", "DESC")->paginate(10);
        return view('blogs.index' ,['blogs' => $blogs]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

      $data =$request->validate([
            "title"=>"required | string",
            "description"=>"required | string",

        ]);
        $data["user_id"] = 1;
        blogs::create($data);

        return to_route('blog.index')->with("success","Blog created succesfuly");
        // $post = new blogs();
        // $post->title = $request->title;
        // $post->description = $request->description;
        // $post->user_id = 1;
        // if($post->save()){
        //     return redirect()->back();
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(blogs $blog)
    {
       
            return view('blogs.show' ,['blog' => $blog]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(blogs $blog)
    {

       return view('blogs.edit',['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, blogs $blog)
    {
        
      $data =$request->validate([
            "title"=>"required | string",
            "description"=>"required | string",

        ]);
        $blog->update($data);
        return to_route('blog.index')->with("success","blogs updated succesfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(blogs $blog)
    {
  
        $blog->delete();
        return to_route('blog.index')->with("success","blogs deleted succesfully");
    }
}
