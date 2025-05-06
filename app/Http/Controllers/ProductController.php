<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function featured() {
        $featuredProducts = Product::where('featured', true)->get();

        return response()->json([
        'success'=> true,
        'data' => $featuredProducts
        ]);
    }
    
}
