@extends('layouts.app')

@section('title', 'Games')

@section('content')
    <style>
        .arcade-hero {
            background: linear-gradient(135deg,#0f172a 0%, #0b1020 40%, #24123b 100%);
            color: #fff;
            padding: 3rem 1rem;
            text-align: center;
            border-bottom: 6px solid #ff3b81;
        }
        .arcade-hero h1 { font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; letter-spacing: 2px; font-weight:800; font-size:2.25rem; margin:0.5rem 0; }
        .arcade-sub { color: #ffd6e8; opacity:0.95; margin-bottom:1rem; }
        .arcade-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem; padding:1rem; }
        .arcade-card { background: linear-gradient(180deg,#0b1220, #071028); border:2px solid rgba(255,59,129,0.08); padding:1rem; border-radius:8px; box-shadow: 0 6px 20px rgba(0,0,0,0.5); color:#e6f7ff; }
        .arcade-card h3 { color:#fff; margin:0 0 0.5rem 0; font-weight:700; }
        .arcade-badge { display:inline-block; padding:0.25rem 0.5rem; background:#ff3b81; color:#fff; border-radius:4px; font-size:0.8rem; font-weight:700; }
        .play-btn { display:inline-block; margin-top:0.75rem; background:#00d1ff; color:#021122; padding:0.5rem 0.75rem; border-radius:6px; text-decoration:none; font-weight:700; }
        @media (min-width:768px){ .arcade-hero h1 { font-size:3rem; } }
    </style>

    <section class="arcade-hero">
        <div class="container">
            <p class="arcade-badge">ARCADE</p>
            <h1>Retro Arcade — Play &amp; Collect</h1>
            <p class="arcade-sub">Fast, free, and nostalgic mini-games. Browse the collection below and launch from the store.</p>
        </div>
    </section>

    {{-- Primary games grid: prefer $products (DB) then fallback to config $games --}}
    <section class="arcade-grid">
        <style>
            .arcade-tile { display:block; position:relative; height:220px; border-radius:12px; background-size:cover; background-position:center; overflow:hidden; transition:transform .18s ease, box-shadow .18s ease; }
            .arcade-tile:hover { transform:translateY(-6px) scale(1.02); box-shadow:0 12px 40px rgba(2,6,23,0.5); }
            .arcade-tile-overlay { display:flex; flex-direction:column; justify-content:flex-end; padding:1rem; height:100%; background:linear-gradient(180deg, rgba(0,0,0,0.0) 40%, rgba(0,0,0,0.55) 100%); color:#fff; }
            .arcade-tile-overlay h3 { margin:0 0 0.35rem 0; font-size:1.15rem; font-weight:800; }
            .arcade-tile-overlay p { margin:0 0 0.5rem 0; color:rgba(255,255,255,0.85); font-size:0.9rem; }
            .arcade-tile .play-btn { display:inline-block; background:#00d1ff;color:#021122;padding:0.5rem 0.65rem;border-radius:8px;font-weight:800;text-decoration:none; }
        </style>
        @if(!empty($products ?? []) && count($products))
            @foreach($products as $product)
                @php
                    // determine image source (prefer attached media)
                    $tileImg = null;
                    if (! empty($product->media) && ! empty($product->media->path)) {
                        try { $tileImg = \Illuminate\Support\Facades\Storage::disk($product->media->disk)->url($product->media->path); } catch (\Exception $e) { $tileImg = null; }
                    }
                    if (! $tileImg) {
                        $desc = strtolower($product->description ?? '');
                        if (\Illuminate\Support\Str::contains($desc, ['space','asteroid','rocket','satellite','cosmic','galaxy'])) { $file = 'rocket.svg'; }
                        elseif (\Illuminate\Support\Str::contains($desc, ['puzz','puzzle','match','brain'])) { $file = 'puzzle.svg'; }
                        elseif (\Illuminate\Support\Str::contains($desc, ['race','racer','racing','car','drive'])) { $file = 'racer.svg'; }
                        else { $file = 'joystick.svg'; }
                        $m = \App\Models\Media::where('filename', $file)->latest()->first();
                        $tileImg = $m ? \Illuminate\Support\Facades\Storage::disk($m->disk)->url($m->path) : asset('assets/games/' . $file);
                    }
                    $href = $product->url ?? url('/shop/' . ($product->product_code ?? ''));
                    $isExternal = ! empty($product->url);
                @endphp
                <a href="{{ $href }}" @if($isExternal) target="_blank" rel="noopener" @endif class="arcade-tile" style="background-image:url('{{ $tileImg }}');">
                    <div class="arcade-tile-overlay">
                        <h3>{{ $product->name }}</h3>
                        @if(!empty($product->description))
                            <p>{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
                        @endif
                        <span class="play-btn">View</span>
                    </div>
                </a>
            @endforeach
        @elseif(!empty($games ?? []) && count($games))
            @foreach($games as $game)
                <div class="arcade-card">
                    @php
                        $gdesc = '';
                        if (is_array($game)) { $gdesc = strtolower($game['description'] ?? ''); }
                        elseif (is_object($game)) { $gdesc = strtolower($game->description ?? ''); }
                        if (\Illuminate\Support\Str::contains($gdesc, ['space','asteroid','rocket','satellite','cosmic','galaxy'])) {
                            $gfile = 'rocket.svg';
                        } elseif (\Illuminate\Support\Str::contains($gdesc, ['puzz','puzzle','match','brain'])) {
                            $gfile = 'puzzle.svg';
                        } elseif (\Illuminate\Support\Str::contains($gdesc, ['race','racer','racing','car','drive'])) {
                            $gfile = 'racer.svg';
                        } else {
                            $gfile = 'joystick.svg';
                        }
                        $gmedia = \App\Models\Media::where('filename', $gfile)->latest()->first();
                        if ($gmedia) {
                            $gicon = \Illuminate\Support\Facades\Storage::url($gmedia->path);
                        } else {
                            $gicon = asset('assets/games/' . $gfile);
                        }
                    @endphp
                    <img src="{{ $gicon }}" alt="{{ $game['title'] ?? $game['name'] }}" style="width:64px;height:64px;display:block;margin-bottom:0.5rem;">
                    <h3>{{ $game['title'] ?? $game['name'] }}</h3>
                    @if(!empty($game['description']))
                        <p class="lead">{{ \Illuminate\Support\Str::limit($game['description'], 120) }}</p>
                    @endif
                    <div>
                        @php
                            $link = '#';
                            $isExternal = false;
                            if (is_object($game) && !empty($game->url)) {
                                $link = $game->url;
                                $isExternal = true;
                            } elseif (is_array($game) && !empty($game['url'])) {
                                $link = $game['url'];
                                $isExternal = true;
                            } elseif (is_array($game) && !empty($game['product_code'])) {
                                $link = url('/shop/' . $game['product_code']);
                            }
                        @endphp
                        <a class="play-btn" href="{{ $link }}" @if($isExternal) target="_blank" rel="noopener" @endif>View</a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="arcade-card">
                <h3>No games found</h3>
                <p class="lead">There are no games available right now. Check back soon or add games in the admin panel.</p>
            </div>
        @endif
    </section>

@endsection
