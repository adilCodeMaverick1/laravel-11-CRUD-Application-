<x-app-layout>
<x-layout>
@include('blogs.nav')
    <a href="/blog/create" class="btn btn-dark m-3">Create</a>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($blogs as $blog)
            <tr>
                <td>{{ $blog->title }}</td>
                <td>{{ $blog->description }}</td>
                <td>
                    <a href="{{ route('blog.edit', $blog) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('blog.show', ['blog' => $blog->id]) }}" class="btn btn-secondary">View</a>
                    <form action="{{ route('blog.destroy', ['blog' => $blog->id]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination svg container">
        {{ $blogs->links() }}
    </div>
    <script>

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('1eaefba807a759b322f9', {
  cluster: 'ap2'
});

var channel = pusher.subscribe('my-channel1');
channel.bind('my-event2', function(data) {
  alert(JSON.stringify(data));
  console.log('Successfully subscribed to channel');

});
</script>
</x-layout>
</x-app-layout>
<style>
    .pagination svg {
    width: 1rem;
    height: 1rem;
}
</style>