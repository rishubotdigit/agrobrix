<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailLog::with('user')->orderBy('created_at', 'desc');

        // Filter by email type
        if ($request->filled('email_type')) {
            $query->where('email_type', $request->email_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $emailLogs = $query->paginate(20);

        return view('admin.email-logs.index', compact('emailLogs'));
    }
}
