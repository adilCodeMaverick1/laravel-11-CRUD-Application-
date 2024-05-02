<x-layout>
@include('blogs.nav')
<h1 style="font-family: cursive;">Generate Blogs Reports</h1>
<form action="{{ route('generate.report') }}" method="GET" id="reportForm" class="row g-3">
    <div class="col-md-3">
        <label for="dateRange" class="form-label">Select Date Range:</label>
        <select id="dateRange" name="date_range" class="form-select form-control" >
            <option value="today">Today</option>
            <option value="weekly">This Week</option>
            <option value="monthly">This Month</option>
            <option value="last_month">Last Month</option>
        </select>
    </div>

    <div class="col-md-3">
        <label for="start_date" class="form-label">Start Date:</label>
        <input type="date" id="start_date" class="form-control" name="start_date" value="{{ old('start_date') }}" required>
    </div>

    <div class="col-md-3">
        <label for="end_date" class="form-label">End Date:</label>
        <input type="date" id="end_date" class="form-control" name="end_date" value="{{ old('end_date') }}" required>
    </div>

    <div class="col-md-3">
        <label for="user_id" class="form-label">User ID:</label>
        <select id="user_id" name="user_id" class="form-select form-control ">
            <option value="">Select User ID</option>
            @foreach($dd_user as $userId)
                <option value="{{ $userId }}">{{ $userId }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 m-3">
        <button type="submit" class="btn btn-success">Generate Report</button>
    </div>
</form>

    <!-- reports -->
    @if(isset($reports) && $reports->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
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

<script>
    document.getElementById('dateRange').addEventListener('change', function() {
        let startDate = document.getElementById('start_date');
        let endDate = document.getElementById('end_date');
        let today = new Date().toISOString().split('T')[0];

        switch (this.value) {
            case 'today':
                startDate.value = today;
                endDate.value = today;
                break;
            case 'weekly':
                let sunday = new Date();
                sunday.setDate(sunday.getDate() - sunday.getDay());
                startDate.value = sunday.toISOString().split('T')[0];
                endDate.value = today;
                break;
            case 'monthly':
                startDate.value = today.substring(0, 8) + '01';
                endDate.value = today;
                break;
            case 'last_month':
                let lastMonth = new Date();
                lastMonth.setMonth(lastMonth.getMonth() - 1);
                startDate.value = lastMonth.toISOString().substring(0, 8) + '01';
                let lastDay = new Date(lastMonth.getFullYear(), lastMonth.getMonth() + 1, 0);
                endDate.value = lastDay.toISOString().split('T')[0];
                break;
            default:
                startDate.value = '';
                endDate.value = '';
                break;
        }
    });
</script>

