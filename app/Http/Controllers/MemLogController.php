<?php

// app/Http/Controllers/MemLogController.php

namespace App\Http\Controllers;

use App\Models\MemLog;
use Illuminate\Http\Request;

class MemLogController extends Controller
{
    // This method will be used to show the initial page with logs
    // public function index()
    // {
    //     // Fetch the latest logs
    //     $logs = MemLog::orderBy('Login_time', 'desc')->take(20)->get();
        
    //     // Pass logs to the view
    //     return view('memlog.index', compact('logs'));
    // }

    // This method handles fetching new logs in real time (AJAX)
    public function fetchLogs(Request $request)
{
    $lastLogID = $request->lastLogID ?? 0;

    // Fetch logs with member names using a JOIN
    $logs = MemLog::join('dbo.m_member', 'dbo.t_memlog.mem_cd', '=', 'dbo.m_member.mem_cd')
                  ->where('dbo.t_memlog.LogID', '>', $lastLogID)
                  ->orWhereNotNull('dbo.t_memlog.Logout_time')
                  ->orderBy('dbo.t_memlog.LogID', 'asc')
                  ->get([
                      'dbo.t_memlog.*', 
                      'dbo.m_member.mem_firstnm', 
                      'dbo.m_member.mem_lstnm'
                  ]);

    return response()->json($logs);
}
// public function filterByDate(Request $request)
// {
//     $date = $request->date;

//     if ($date) {
//         $logs = MemLog::with('member')
//             ->whereDate('Login_time', $date)
//             ->orWhereDate('Logout_time', $date)
//             ->orderBy('LogID', 'asc')
//             ->get();

//         return response()->json($logs);
//     }

//     return response()->json([]);
// }

    

    public function index()
    {
        $logs = MemLog::orderBy('Login_time', 'desc')->take(20)->get();
        return view('memlog.index', compact('logs'));
    }
}