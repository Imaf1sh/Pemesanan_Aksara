<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function customer()
    {
        $products = Product::all();
        $coffee = $products->where('category', 'coffee');
        $nonCoffee = $products->where('category', 'non-coffee');
        $snacks = $products->where('category', 'snack');

        return view('customer', compact('coffee', 'nonCoffee', 'snacks'));
    }

    public function pos()
    {
        return view('pos');
    }

    public function kasir()
    {
        return view('kasir');
    }
}
