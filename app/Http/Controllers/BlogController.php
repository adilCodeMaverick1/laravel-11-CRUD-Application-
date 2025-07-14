<?php

namespace App\Http\Controllers;
use App\Events\NewBlogPost;

use App\Models\blogs;
use App\Models\Product;
use Illuminate\Http\Request;
use function Pest\Laravel\json;
use App\Models\User;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blogs::orderBy("created_at", "DESC")->paginate(10);
        return view('blogs.index', ['blogs' => $blogs]);
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

        $post_data = $request->validate([
            "title" => "required | string",
            "description" => "required | string",

        ]);
        $post_data["user_id"] = auth()->id();

        blogs::create($post_data);

        $data = [
            'title' => $post_data['title'],
            'description' => $post_data['description'],
            'user_id' => $post_data['user_id'], // Assuming 'user_id' is a field in your 'blogs' table
        ];


        event(new NewBlogPost($data));
        return to_route('blog.index')->with("success", "Blog created succesfuly");
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
        $blog->description = null;
        $data = [
            'title' => $blog->title,
            'user' => $blog->user->name,
            'extra' => [
                [
                    'description' => $blog->description,
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at,
                    'user_id' => $blog->user_id,
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'blog' => $data,
            'message' => 'Blog retrieved successfully'

        ]);
        // return view('blogs.show', ['blog' => $blog]);
        // return response()->json($blog);
        // return view('blogs.show', compact('blog'));
        // return view('blogs.show', ['blog' => $blog]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(blogs $blog)
    {

        return view('blogs.edit', ['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, blogs $blog)
    {

        $data = $request->validate([
            "title" => "required | string",
            "description" => "required | string",

        ]);
        $blog->update($data);
        return to_route('blog.index')->with("success", "blogs updated succesfully");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(blogs $blog)
    {

        $blog->delete();
        return to_route('blog.index')->with("success", "blogs deleted succesfully");
    }
    // public function search(Request $request)
    // {
    //     $name = $request->input('name');
    //   $data=blogs::where("title","like","%".$name."%")->get();
    //     return view('blogs.search',['data'=>$data]);
    // }
    public function search(Request $request)
    {
        $name = $request->validate([
            "name" => "required | string"
        ]);
        $name = $request->input('name');
        $data = blogs::where('title', 'like', "%$name%")->get();
        return view('blogs.search', ['data' => $data]);
    }
    public function check(Request $request)
    {
        $name = $request->validate([
            "name" => "required | string"
        ]);
        $name = $request->input('name');
        $data = blogs::where('title', 'like', "%$name%")->get();
        return view('blogs.search', compact('data'));
    }
    public function addpropage()
    {
        $products = Product::get();
        return view('check.index', compact('products'));
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


    public function getUserOneBlogs()
    {

        $blogs = Blogs::where('user_id', 1)
            ->pluck('title')
        ;
        $staticBlogs = collect([
            'Blog 1',
            'Blog 2',
            'Blog 3',
            'Blog 4',
            'Blog 5',
        ]);


        $blogs = array_merge($blogs->toArray(), $staticBlogs->toArray());



        // $data = [
        //     'data' => $blogs->items(),
        //     'current_page' => $blogs->currentPage(),
        //     'last_page' => $blogs->lastPage(),
        //     'per_page' => $blogs->perPage(),
        //     'total' => $blogs->total(),
        //     'next_page_url' => $blogs->nextPageUrl(),
        //     'prev_page_url' => $blogs->previousPageUrl(),
        // ];
        return response()->json([
            'success' => true,
            'data' => $blogs,
            'message' => 'Blogs retrieved successfully'
        ]);
        // return view('blogs.user1', ['blogs' => $blogs]);

    }

    public function availableToBuy()
    {
        $availableBlogs = Blogs::where('user_id', '!=', auth()->user()->id)->paginate(10);
        return view('blogs.available', ['blogs' => $availableBlogs]);
    }

    public function loadMoreBlogs(Request $request)
    {
        // Get the current page from the AJAX request (defaults to 1 if not present)
        $page = $request->input('page', 1);

        // Fetch the next set of blogs
        $availableBlogs = Blogs::where('user_id', '!=', auth()->user()->id)->paginate(10, ['*'], 'page', $page);

        // Check if there are more items to load
        if ($availableBlogs->isEmpty()) {
            return response()->json(['data' => [], 'next_page_url' => null]);
        }

        // Return the new blog data and the URL for the next page as JSON
        return response()->json([
            'data' => $availableBlogs->items(), // Get only the items, not the full paginator object
            'next_page_url' => $availableBlogs->nextPageUrl()
        ]);
    }

    public function allAuthors()
    {
        $authors = User::where('id', '!=', auth()->user()->id)->get();
        return view('blogs.all-authors', ['authors' => $authors]);
    }


}
