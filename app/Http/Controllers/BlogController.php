<?php

namespace App\Http\Controllers;
use App\Events\NewBlogPost;

use App\Models\blogs;
use App\Models\Product;
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

      $post_data =$request->validate([
            "title"=>"required | string",
            "description"=>"required | string",

        ]);
        $post_data["user_id"] = auth()->id();

         blogs::create($post_data);
        
        $data = [
            'title' => $post_data['title'],
            'description' => $post_data['description'],
            'user_id' => $post_data['user_id'], // Assuming 'user_id' is a field in your 'blogs' table
        ];
        
    
        event(new NewBlogPost($data));
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
    // public function search(Request $request)
    // {
    //     $name = $request->input('name');
    //   $data=blogs::where("title","like","%".$name."%")->get();
    //     return view('blogs.search',['data'=>$data]);
    // }
    public function search(Request $request){
        $name= $request->validate([
            "name"=>"required | string"
        ]);
        $name = $request->input('name');
        $data = blogs::where('title', 'like', "%$name%")->get();
        return view('blogs.search', ['data' => $data]);
    }
    public function check(Request $request){
        $name= $request->validate([
            "name"=>"required | string"
        ]);
        $name = $request->input('name');
        $data = blogs::where('title', 'like', "%$name%")->get();
        return view('blogs.search',compact('data'));
    }
    public function addpropage(){
        $products = Product::get();
        return view('check.index',compact('products'));
    }
    public function addProduct(Request $request)
    {
        $productId = $request->input('productId');
        $product = Product::find($productId);
    
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        // Add product to the session
        $cart = session()->get('cart', []);
        $cart[] = $product;
        session()->put('cart', $cart);
    
        // Get all products in the cart
        $allProducts = session()->get('cart', []);
    
        return response()->json(['products' => $allProducts]);
    }
    

}
