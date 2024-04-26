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
</x-layout>
<style>
    .pagination svg {
    width: 1rem;
    height: 1rem;
}
</style>