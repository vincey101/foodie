<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RestaurantSearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
            'key' => config('services.google.places_api_key'),
            'location' => $request->lat . ',' . $request->lng,
            'radius' => 5000, // 5km radius
            'type' => 'restaurant',
        ]);

        $restaurants = [];
        if ($response->successful()) {
            $results = $response->json()['results'];
            foreach ($results as $place) {
                $restaurants[] = [
                    'name' => $place['name'],
                    'address' => $place['vicinity'],
                    'rating' => $place['rating'] ?? null,
                    'photo' => isset($place['photos'][0]) 
                        ? "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference={$place['photos'][0]['photo_reference']}&key=" . config('services.google.places_api_key')
                        : null,
                    'place_id' => $place['place_id'],
                ];
            }
        }

        return view('restaurants.search', [
            'address' => $request->address,
            'latitude' => $request->lat,
            'longitude' => $request->lng,
            'restaurants' => $restaurants
        ]);
    }
} 