<?php

namespace App\Http\Controllers;

use App\Services\NikniqClient;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function index(NikniqClient $nik)
    {
        $items = [];
        try {
            $items = $nik->fetchLatest(12);
        } catch (\Exception $e) {
            $items = [];
        }

        $games = config('games.list', []);

        return view('themes.games.frontpage', compact('items', 'games'));
    }
}
