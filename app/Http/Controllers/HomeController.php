<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Models\Property;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role) {
            $plans = Plan::forRole(Auth::user()->role)->where('status', 'active')->get();
            \Log::info('HomeController@index plans fetched for role', ['role' => Auth::user()->role, 'plans_count' => $plans->count(), 'plans' => $plans->pluck('name', 'role')]);
        } else {
            $plans = Plan::where('status', 'active')->get();
            \Log::info('HomeController@index plans fetched for guest', ['plans_count' => $plans->count(), 'plans' => $plans->pluck('name', 'role')]);
        }

        $currentPlanId = null;
        $currentPlanPrice = 0;
        if (Auth::check()) {
            $activePurchase = PlanPurchase::where('user_id', Auth::id())
                ->where('status', 'activated')
                ->where(function($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })->first();
            if ($activePurchase) {
                $currentPlanId = $activePurchase->plan_id;
                $currentPlanPrice = $activePurchase->plan->price ?? 0;
            }
        }

        $featuredProperties = Property::with(['owner', 'amenities', 'district.state'])->where('status', 'approved')->where('featured', true)->limit(4)->get();
        $latestProperties = Property::with(['owner', 'amenities', 'district.state'])->where('status', 'approved')->orderBy('created_at', 'desc')->limit(4)->get();

        // Add wishlist status for authenticated buyers
        if (Auth::check() && Auth::user()->role === 'buyer') {
            $user = Auth::user();
            $wishlistPropertyIds = Wishlist::where('user_id', $user->id)->pluck('property_id')->toArray();

            $featuredProperties->transform(function ($property) use ($wishlistPropertyIds) {
                $property->is_in_wishlist = in_array($property->id, $wishlistPropertyIds);
                return $property;
            });

            $latestProperties->transform(function ($property) use ($wishlistPropertyIds) {
                $property->is_in_wishlist = in_array($property->id, $wishlistPropertyIds);
                return $property;
            });
        }

        // Properties in Selected State
        $selectedState = request('state');
        if (!$selectedState && Auth::check() && Auth::user()->address) {
            // Attempt to extract state from user address if possible, or just default
            // For now, let's default to Karnataka if not provided, as per request example
            $selectedState = 'Karnataka'; 
        }
        $selectedState = $selectedState ?: 'Karnataka';

        $selectedStateProperties = Property::with(['owner', 'amenities', 'district'])
            ->where('status', 'approved')
            ->where('state', $selectedState)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
            
        // State Summary
        $stateSummary = Property::join('states', 'properties.state', '=', 'states.name')
            ->where('properties.status', 'approved')
            ->where('states.is_active', true)
            ->select('properties.state', 'states.image', 'states.icon', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('properties.state', 'states.image', 'states.icon')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Category Summary
        $categorySummary = Property::where('status', 'approved')
            ->select('land_type', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('land_type')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return view('home', compact('plans', 'featuredProperties', 'latestProperties', 'currentPlanId', 'currentPlanPrice', 'selectedState', 'selectedStateProperties', 'stateSummary', 'categorySummary'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function features()
    {
        return view('pages.features');
    }

    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    public function roles()
    {
        return view('pages.roles');
    }

    public function pricing()
    {
        if (Auth::check() && Auth::user()->role) {
            $plans = Plan::forRole(Auth::user()->role)->where('status', 'active')->get();
            \Log::info('HomeController@pricing plans fetched for role', ['role' => Auth::user()->role, 'plans_count' => $plans->count(), 'plans' => $plans->pluck('name', 'role')]);
        } else {
            $plans = Plan::where('status', 'active')->get();
            \Log::info('HomeController@pricing plans fetched for guest', ['plans_count' => $plans->count(), 'plans' => $plans->pluck('name', 'role')]);
        }
        return view('pages.pricing', compact('plans'));
    }

    public function refund()
    {
        return view('refund');
    }

    public function forBuyers()
    {
        $plans = Plan::where('role', 'buyer')->where('status', 'active')->get();
        
        $currentPlanId = null;
        $currentPlanPrice = 0;
        if (Auth::check()) {
            $activePurchase = \App\Models\PlanPurchase::where('user_id', Auth::id())
                ->where('status', 'activated')
                ->where(function($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })->first();
            if ($activePurchase) {
                $currentPlanId = $activePurchase->plan_id;
                $currentPlanPrice = $activePurchase->plan->price ?? 0;
            }
        }

        return view('pages.for-buyers', compact('plans', 'currentPlanId', 'currentPlanPrice'));
    }

    public function forSellers()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'owner') {
                $plans = Plan::where('role', 'owner')->where('status', 'active')->get();
            } elseif ($user->role === 'agent') {
                $plans = Plan::where('role', 'agent')->where('status', 'active')->get();
            } elseif ($user->role === 'admin') {
                $ownerPlans = Plan::where('role', 'owner')->where('status', 'active')->get();
                $agentPlans = Plan::where('role', 'agent')->where('status', 'active')->get();
                $plans = $ownerPlans->merge($agentPlans);
            } else {
                // Buyer or other roles
                $plans = collect();
            }
        } else {
            // Guest users see all plans
            $ownerPlans = Plan::where('role', 'owner')->where('status', 'active')->get();
            $agentPlans = Plan::where('role', 'agent')->where('status', 'active')->get();
            $plans = $ownerPlans->merge($agentPlans);
        }

        $currentPlanId = null;
        $currentPlanPrice = 0;
        if (Auth::check()) {
            $activePurchase = \App\Models\PlanPurchase::where('user_id', Auth::id())
                ->where('status', 'activated')
                ->where(function($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })->first();
            if ($activePurchase) {
                $currentPlanId = $activePurchase->plan_id;
                $currentPlanPrice = $activePurchase->plan->price ?? 0;
            }
        }
        
        return view('pages.for-sellers', compact('plans', 'currentPlanId', 'currentPlanPrice'));
    }

    public function postProperty()
    {
        return view('pages.post-property');
    }

    public function allStates()
    {
        $states = Property::join('states', 'properties.state', '=', 'states.name')
            ->where('properties.status', 'approved')
            ->where('states.is_active', true)
            ->select('properties.state', 'states.image', 'states.icon', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('properties.state', 'states.image', 'states.icon')
            ->orderByDesc('total')
            ->get();

        return view('pages.all-states', compact('states'));
    }
}