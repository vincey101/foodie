<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function show(): View
    {
        return view('cart');
    }

    public function index(): JsonResponse
    {
        // Return cart items as JSON
        return response()->json(['items' => []]);
    }

    public function history()
    {
        $cart = Session::get('cart', []);
        return view('cart.history', [
            'items' => $cart
        ]);
    }

    public function add(Request $request)
    {
        $cart = Session::get('cart', []);
        
        $item = [
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity ?? 1,
            'image' => $request->image
        ];

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$request->id] = $item;
        }

        Session::put('cart', $cart);
        return response()->json(['message' => 'Item added to cart', 'cartCount' => count($cart)]);
    }

    public function remove(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            Session::put('cart', $cart);
        }

        return response()->json(['message' => 'Item removed from cart', 'cartCount' => count($cart)]);
    }
} 