<x-layout>
<h1>EditBlog</h1>
<form action="{{ route('blog.update',$blog) }}" method="POST">
    @method("PUT")
    @csrf
    
<a href="/blog" class="btn btn-success">show</a>
    <div class="mb-3 ">
        <label for="title" class="form-label text-danger h1 ">Title</label>
        <input type="text" class="form-control" id="title"  value="{{$blog->title}}" name="title" placeholder="Enter title">
        @error("title")
            <span class="text-danger">{{$message}}</span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label h1">Description</label>
        <textarea class="form-control" id="description" value="{{$blog->description}}" name="description" rows="3" placeholder="Enter description"></textarea>
        @error("description")
            <span class="text-danger">{{$message}}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</x-layout>