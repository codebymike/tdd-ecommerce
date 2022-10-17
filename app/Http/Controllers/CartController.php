<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function store()
    {
        session()->push('cart', [
            'id' => request('id'),
            'qty' => 1, // default qty
        ]);

        return redirect('/cart');
    }
}