<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyReport;
use Illuminate\Http\Request;

class PropertyReportController extends Controller
{
    public function index(Request $request)
    {
        $query = PropertyReport::with(['property', 'user'])->latest();

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->whereHas('property', function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', $searchTerm)
                  ->orWhere('id', 'LIKE', $searchTerm);
            });
        }

        $reports = $query->paginate(10)->withQueryString();
        return view('admin.reports.index', compact('reports'));
    }

    public function show(PropertyReport $report)
    {
        $report->load(['property.owner', 'user']);
        return view('admin.reports.show', compact('report'));
    }
}
