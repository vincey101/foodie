<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Order creation logic will go here
        return response()->json(['message' => 'Order created successfully']);
    }
} 