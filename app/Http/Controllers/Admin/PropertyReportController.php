<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyReport;
use Illuminate\Http\Request;

class PropertyReportController extends Controller
{
    public function index()
    {
        $reports = PropertyReport::with(['property', 'user'])->latest()->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    public function show(PropertyReport $report)
    {
        $report->load(['property.owner', 'user']);
        return view('admin.reports.show', compact('report'));
    }
}
