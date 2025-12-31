<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SmsLog::with('user')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Filter by gateway
        if ($request->filled('gateway')) {
            $query->gateway($request->gateway);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->type($request->type);
        }

        // Search by mobile
        if ($request->filled('search')) {
            $query->where('mobile', 'like', '%' . $request->search . '%');
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        return view('admin.sms-logs.index', compact('logs'));
    }

    public function show(SmsLog $log)
    {
        $log->load('user');
        return view('admin.sms-logs.show', compact('log'));
    }
}
