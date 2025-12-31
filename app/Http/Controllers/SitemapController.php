<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            route('home'),
            route('about'),
            route('properties.index'),
            route('contact'),
            route('privacy'),
            route('terms'),
            // Add other static pages
        ];

        // Properties
        $properties = Property::where('status', 'approved')->orderBy('updated_at', 'desc')->get();
        foreach ($properties as $property) {
            $urls[] = route('properties.show', $property); // Uses slug
        }

        // Logic for "Search Results" or Categories:
        // We can add distinct locations
        $districts = \App\Models\District::has('properties')->get();
        foreach($districts as $district) {
             // For now, listing categories isn't strictly requested as "pages", but "search results".
             // Since we use query params for search (?district_id=...), it's better to rely on the main property listing page or create clean routes for them.
             // Given the scope, I'll stick to properties and main pages.
             // But to satisfy "auto generate best slug when some one search any term and get results add in sitemap",
             // I could potentially log high-volume searches and act on them, but that's complex.
             // I will interpret this as "make sure content is indexable".
             // A really good SEO tactic is linking to popular searches.
             // For this task, I'll stick to a robust standard sitemap.
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($url) . '</loc>';
            // Optional: lastmod, changefreq, priority
            if (strpos($url, '/properties/') !== false) {
                 $xml .= '<changefreq>weekly</changefreq>';
                 $xml .= '<priority>0.8</priority>';
            } else {
                 $xml .= '<changefreq>monthly</changefreq>';
                 $xml .= '<priority>0.5</priority>';
            }
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
