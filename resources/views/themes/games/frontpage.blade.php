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
        @if(!empty($products ?? []) && count($products))
            @foreach($products as $product)
                <div class="arcade-card">
                    <h3>{{ $product->name }}</h3>
                    @if(!empty($product->description))
                        <p class="lead">{{ \\Illuminate\\Support\\Str::limit($product->description, 120) }}</p>
                    @endif
                    <div>
                        <a class="play-btn" href="{{ url('/shop/' . ($product->product_code ?? '')) }}">View</a>
                    </div>
                </div>
            @endforeach
        @elseif(!empty($games ?? []) && count($games))
            @foreach($games as $game)
                <div class="arcade-card">
                    <h3>{{ $game['title'] ?? $game['name'] }}</h3>
                    @if(!empty($game['description']))
                        <p class="lead">{{ \\Illuminate\\Support\\Str::limit($game['description'], 120) }}</p>
                    @endif
                    <div>
                        @php
                            $link = '#';
                            if (is_array($game) && !empty($game['product_code'])) {
                                $link = url('/shop/' . $game['product_code']);
                            }
                        @endphp
                        <a class="play-btn" href="{{ $link }}">View</a>
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
