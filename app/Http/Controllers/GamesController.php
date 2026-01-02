<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GamesController extends Controller
{
    public function index()
    {
        if (! config('games.enabled')) {
            abort(404);
        }
        $category = config('games.category', 'Game');

        $products = Product::query()
            ->with('media')
            ->where('category', $category)
            ->orderBy('name')
            ->get();

        // Do not fall back to config list by default; only show DB products.

        return view('themes.games.frontpage', [
            'products' => $products,
        ]);
    }
}
