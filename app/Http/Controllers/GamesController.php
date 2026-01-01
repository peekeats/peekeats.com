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
            ->where('category', $category)
            ->orderBy('name')
            ->get();

        // Fallback to config list if enabled and DB returned no products
        if ($products->isEmpty() && config('games.fallback_to_config', true)) {
            $games = config('games.list', []);
            $products = collect($games)->map(function ($g) {
                return (object) [
                    'name' => $g['title'] ?? ($g['name'] ?? 'Untitled'),
                    'product_code' => $g['slug'] ?? Str::slug($g['title'] ?? 'game'),
                    'description' => $g['description'] ?? null,
                    'price' => $g['price'] ?? 0.00,
                    'duration_months' => $g['duration_months'] ?? 0,
                    'category' => $g['category'] ?? 'Game',
                    'url' => $g['url'] ?? null,
                ];
            })->all();
        }

        return view('games.index', [
            'products' => $products,
        ]);
    }
}
