<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchProductsController extends Controller
{
    public function index()
    {
        $items = Product::get();
        return view('search', compact('items'));
    }
}