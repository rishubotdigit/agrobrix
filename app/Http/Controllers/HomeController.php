<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Property;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $featuredProperties = Property::with(['owner', 'agent', 'amenities'])->where('status', 'approved')->where('featured', true)->limit(8)->get();
        $latestProperties = Property::with(['owner', 'agent', 'amenities'])->where('status', 'approved')->orderBy('created_at', 'desc')->limit(10)->get();

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

        return view('home', compact('plans', 'featuredProperties', 'latestProperties'));
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
        // Buyers should not access pricing page
        if (Auth::check() && Auth::user()->role === 'buyer') {
            abort(403, 'Access denied. Buyers do not have pricing plans.');
        }

        $plans = Plan::all();
        return view('pages.pricing', compact('plans'));
    }
    public function forBuyers()
    {
        return view('pages.for-buyers');
    }

    public function forSellers()
    {
        return view('pages.for-sellers');
    }

    public function postProperty()
    {
        return view('pages.post-property');
    }
}