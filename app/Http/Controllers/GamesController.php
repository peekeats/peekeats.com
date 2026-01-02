<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GamesController extends Controller
{
    public function index(Request $request)
    {
        if (! config('games.enabled')) {
            abort(404);
        }
        $category = config('games.category', 'Game');
        $search = trim((string) $request->query('q', ''));

        $productsQuery = Product::query()
            ->with('media')
            ->where('category', $category);

        if ($search !== '') {
            $productsQuery->where(function ($b) use ($search) {
                $b->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('product_code', 'like', '%' . $search . '%');
            });
        }

        $products = $productsQuery->orderBy('name')->get();

        // Do not fall back to config list by default; only show DB products.

        return view('themes.games.frontpage', [
            'products' => $products,
            'q' => $search,
        ]);
    }
}
