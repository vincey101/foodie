<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class FoodController extends Controller
{
    public function index(): View
    {
        $cartCount = count(Session::get('cart', []));
        
        // Get unique categories with their first item's image
        $categories = FoodItem::select('category')
            ->distinct()
            ->get()
            ->map(function ($item) {
                $categoryImage = FoodItem::where('category', $item->category)
                    ->first()
                    ->image_path;
                return [
                    'name' => $item->category,
                    'slug' => str()->slug($item->category),
                    'image' => $categoryImage
                ];
            });

        // Get all food items grouped by category
        $foodsByCategory = FoodItem::where('is_available', true)
            ->get()
            ->groupBy('category');

        return view('welcome', [
            'cartCount' => $cartCount,
            'categories' => $categories,
            'foodsByCategory' => $foodsByCategory
        ]);
    }

    public function menu(): View
    {
        $foodItems = FoodItem::where('is_available', true)
            ->orderBy('category')
            ->get();

        return view('menu', compact('foodItems'));
    }

    public function category(string $category): View
    {
        $foodItems = FoodItem::where('category', $category)
            ->where('is_available', true)
            ->get();

        return view('category', compact('foodItems', 'category'));
    }

    public function area(string $area): View
    {
        // Later we'll filter restaurants by area
        return view('area', [
            'area' => str_replace('-', ' ', ucwords($area)),
            'restaurants' => []
        ]);
    }
} 