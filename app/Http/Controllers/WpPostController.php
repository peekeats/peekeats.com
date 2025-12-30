<?php

namespace App\Http\Controllers;

use App\Models\WpPost;
use Illuminate\Http\Request;

class WpPostController extends Controller
{
    public function index(Request $request)
    {
        $posts = WpPost::query()
            ->where('post_status', 'publish')
            ->where('post_type', 'post')
            ->orderBy('post_date', 'desc')
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = WpPost::where('post_name', $slug)
            ->where('post_status', 'publish')
            ->where('post_type', 'post')
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }
}
