<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\blogs;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    function report()
    {
        $blogs = Blogs::all();
        $dd_user = Blogs::distinct('user_id')->pluck('user_id');
        // dd($dd_user);
        return view('report.index', compact('blogs', 'dd_user'));
    }
//     public function generateReport (Request $request){
// $startDate=$request->start_date;
// $endDate=$request->end_date;

//         $reports=blogs::whereDate('created_at','>=',$startDate)
//         ->whereDate('created_at','<=',$endDate)
//         ->get();
//     return view('report.result', ['reports' => $reports]);


//     }
public function generateReport(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'user_id' => 'required|numeric',
    ]);

    $startDate = $validatedData['start_date'];
    $endDate = $validatedData['end_date'];
    $userId = $validatedData['user_id'];

    $reports = blogs::whereDate('created_at', '<=', $endDate)
    ->whereDate('created_at', '>=', $startDate)
    ->where('user_id', $userId)
    ->get();

   
    $dd_user = Blogs::distinct('user_id')->pluck('user_id');
    
    return view('report.index', ['reports' => $reports, 'dd_user' => $dd_user]);
}



// public function generateReport(Request $request)
// {
//     $startDate = $request->input('start_date');
//     $endDate = $request->input('end_date');
//     $userId = $request->input('user_id');

//     // Swap dates if start date is after end date
//     if ($startDate > $endDate) {
//         $temp = $startDate;
//         $startDate = $endDate;
//         $endDate = $temp;
//     }

//     // Perform your database query using $startDate, $endDate, and $userId
//     $query = blogs::where('user_id', $userId)
//                   ->whereBetween('created_at', [$startDate, $endDate]);

//     $reports = $query->get();

//     // Debug the reports
//     //dd($reports);
//     $dd_user = Blogs::distinct('user_id')->pluck('user_id');

//     return view('report.index', ['reports' => $reports,'dd_user' =>$dd_user ]);
// }



}
