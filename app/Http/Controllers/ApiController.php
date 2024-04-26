<?php

namespace App\Http\Controllers;
use App\Models\blogs;

use Illuminate\Http\Request;

class ApiController extends Controller
{
//   function getBlogs($blog = null){

//     return $blog?blogs::find($blog):blogs::all();
    
//   }
function getBlogs($blog = null) {
    //agar id na ho to sare dokhao 
    return $blog ? blogs::find($blog) : blogs::all();
}
function create(Request $request) {
    $post = new blogs;
    $post->title = $request->title;
    $post->description = $request->description;
    $post->user_id = 1;
    
    if ($post->save()) {
        return ["blog" => "sent success"];
    } else {
        return ["blog" => "sent failed"];
    }
}
function update(Request $request){
$device =  blogs::find($request->id);
$device->title = $request->title;
$device->description = $request->description;

if($device->save()){
    return ["blog" => "updated success "];

}
else{
    return ["blog" => "updated failed"];

}

}

function search($name){
    return blogs::where("title","like","%".$name."%")->get();

}
function delete(Request $request){
    $device =  blogs::find($request->id);
    if($device->delete()){
        return ["blog" => "deleted success "];

    }
    else{
        return ["blog" => "deleted failed"];

    }

}
}
