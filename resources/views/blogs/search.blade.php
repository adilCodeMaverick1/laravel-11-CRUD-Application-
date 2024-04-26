<x-layout>
@include('blogs.nav')

    @if ($data->isEmpty())
        <h1>No results</h1>

   @else
@foreach ($data as $blog)
<div class="card">
        <div class="card-body">

            <h5 class="card-title">{{ $blog->title }}</h5>
            <p class="card-text">{{ $blog->description }}</p>
        </div>

    </div>
@endforeach
   
@endif




</x-layout>