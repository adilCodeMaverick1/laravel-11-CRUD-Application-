<x-layout>
@include('blogs.nav')
<h1 style="font-family: cursive;">Generate Blogs Reports</h1>
<form action="{{ route('generate.report') }}" method="GET" id="reportForm" class="row g-3">
 

    <div class="col-md-3">
        <label for="start_date" class="form-label">Start Date:</label>
        <input type="date" id="start_date" class="form-control" name="start_date" value="{{ old('start_date') }}" required>
    </div>

    <div class="col-md-3">
        <label for="end_date" class="form-label">End Date:</label>
        <input type="date" id="end_date" class="form-control" name="end_date" value="{{ old('end_date') }}" required>
    </div>

 

    <div class="col-md-12 m-3">
        <button type="submit" class="btn btn-success">Generate Report</button>
    </div>
</form>


</x-layout>

