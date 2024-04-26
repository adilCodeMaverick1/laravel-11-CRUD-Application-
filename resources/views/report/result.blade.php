    <x-layout>
        @include('blogs.nav')
        @if(isset($reports) && $reports->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Description</th>
                        <!-- Add more table headers as needed -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->user_id }}</td>
                            <td>{{ $report->created_at }}</td>
                            <td>{{ $report->title }}</td>
                            <td>{{ $report->description }}</td>
                            <!-- Add more table cells as needed -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif(isset($reports) && $reports->isEmpty())
        <div class="alert alert-success">No reports found.</div>
    @endif
    </x-layout>