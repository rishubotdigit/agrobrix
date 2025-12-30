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
        $totalPaymentsAmount = Payment::where('status', 'completed')->sum('amount');
        $totalPaymentsCount = Payment::where('status', 'completed')->count();
        $pendingVersions = PropertyVersion::where('status', 'pending')->count();
        $pendingPlanPurchases = PlanPurchase::where('status', 'pending')->count();

        // User role breakdown
        $userRoles = User::selectRaw('role, COUNT(*) as count')->groupBy('role')->get();
        $roleLabels = $userRoles->pluck('role')->map(fn($r) => ucfirst($r))->toArray();
        $roleCounts = $userRoles->pluck('count')->toArray();

        // Latest Activity
        $latestProperties = Property::with(['owner', 'district.state'])->latest()->take(5)->get();
        $recentPayments = Payment::with('user')->where('status', 'completed')->latest()->take(5)->get();
        $recentPurchases = PlanPurchase::with(['user', 'plan'])->latest()->take(5)->get();

        $usersData = User::selectRaw('date(created_at) as date, COUNT(*) as count')->where('created_at', '>=', now()->subDays(7))->groupBy('date')->orderBy('date')->get();
        $propertyData = Property::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
        $revenueData = Payment::selectRaw("YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total")->where('status', 'completed')->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();

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

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProperties', 'totalPaymentsAmount', 'totalPaymentsCount', 
            'pendingVersions', 'pendingPlanPurchases', 'userLabels', 'userData', 
            'propertyLabels', 'propertyCounts', 'revenueLabels', 'revenueDataValues',
            'roleLabels', 'roleCounts', 'latestProperties', 'recentPayments', 'recentPurchases'
        ));
    }

    public function getChartData()
    {
        $usersData = User::selectRaw('date(created_at) as date, COUNT(*) as count')->where('created_at', '>=', now()->subDays(7))->groupBy('date')->orderBy('date')->get();
        $propertyData = Property::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
        $revenueData = Payment::selectRaw("YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total")->where('status', 'completed')->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();

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
