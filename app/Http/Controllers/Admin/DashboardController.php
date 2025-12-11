<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Payment;
use App\Models\PropertyVersion;
use App\Models\PlanPurchase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalProperties = Property::count();
        $totalPayments = Payment::count();
        $pendingVersions = PropertyVersion::where('status', 'pending')->count();
        $pendingPlanPurchases = PlanPurchase::where('status', 'purchased')->count();

        $usersData = User::selectRaw('date(created_at) as date, COUNT(*) as count')->where('created_at', '>=', now()->subDays(7))->groupBy('date')->orderBy('date')->get();
        $propertyData = Property::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
        $revenueData = Payment::selectRaw("strftime('%Y', created_at) as year, strftime('%m', created_at) as month, SUM(amount) as total")->where('status', 'completed')->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();

        // Prepare user chart data
        $userLabels = [];
        $userData = [];
        foreach ($usersData as $item) {
            $date = \Carbon\Carbon::parse($item->date);
            $userLabels[] = $date->format('M j');
            $userData[] = $item->count;
        }

        // Prepare property chart data
        $propertyLabels = $propertyData->pluck('status')->toArray();
        $propertyCounts = $propertyData->pluck('count')->toArray();

        // Prepare revenue chart data
        $revenueLabels = [];
        $revenueDataValues = [];
        foreach ($revenueData as $item) {
            $revenueLabels[] = \Carbon\Carbon::create($item->year, $item->month)->format('M Y');
            $revenueDataValues[] = $item->total;
        }

        return view('admin.dashboard', compact('totalUsers', 'totalProperties', 'totalPayments', 'pendingVersions', 'pendingPlanPurchases', 'userLabels', 'userData', 'propertyLabels', 'propertyCounts', 'revenueLabels', 'revenueDataValues'));
    }

    public function getChartData()
    {
        $usersData = User::selectRaw('date(created_at) as date, COUNT(*) as count')->where('created_at', '>=', now()->subDays(7))->groupBy('date')->orderBy('date')->get();
        $propertyData = Property::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
        $revenueData = Payment::selectRaw("strftime('%Y', created_at) as year, strftime('%m', created_at) as month, SUM(amount) as total")->where('status', 'completed')->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();

        // Prepare user chart data
        $userLabels = [];
        $userData = [];
        foreach ($usersData as $item) {
            $date = \Carbon\Carbon::parse($item->date);
            $userLabels[] = $date->format('M j');
            $userData[] = $item->count;
        }

        // Prepare property chart data
        $propertyLabels = $propertyData->pluck('status')->toArray();
        $propertyCounts = $propertyData->pluck('count')->toArray();

        // Prepare revenue chart data
        $revenueLabels = [];
        $revenueDataValues = [];
        foreach ($revenueData as $item) {
            $revenueLabels[] = \Carbon\Carbon::create($item->year, $item->month)->format('M Y');
            $revenueDataValues[] = $item->total;
        }

        return response()->json([
            'usersData' => [
                'labels' => $userLabels,
                'data' => $userData
            ],
            'propertyData' => [
                'labels' => $propertyLabels,
                'data' => $propertyCounts
            ],
            'revenueData' => [
                'labels' => $revenueLabels,
                'data' => $revenueDataValues
            ]
        ]);
    }
}
