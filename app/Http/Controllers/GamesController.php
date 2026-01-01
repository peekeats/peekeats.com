<?php

namespace App\Http\Controllers;

use App\Services\NikniqClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GamesController extends Controller
{
    public function index(NikniqClient $nik)
    {
        if (! config('games.enabled')) {
            abort(404);
        }
        // Curated products from config
        $games = config('games.list', []);

        // Map to a product-like structure for the listing view
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

        return view('games.index', [
            'products' => $products,
        ]);
    }
}
