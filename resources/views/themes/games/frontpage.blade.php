@extends('layouts.app')

@section('title', 'Games')

@section('content')
    <header class="hero">
        <div class="card">
            <p class="eyebrow">Games</p>
            <h1>Games and Playlists</h1>
            <p class="lead">Curated games and related content. External sources may be shown where available.</p>
        </div>
    </header>

    {{-- Curated games list from config/games.php --}}
    @if(!empty($games ?? []))
        <section style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
            @foreach($games as $game)
                <div class="card">
                    <h3>{{ $game['title'] }}</h3>
                    <p class="lead">{{ $game['description'] }}</p>
                </div>
            @endforeach
        </section>
    @endif

    <section style="margin-top:1.5rem;">
        <div class="card">
            <h2>Embedding external content</h2>
            <p class="lead">To surface external content without copying copyrighted text, fetch a feed or API in a controller, transform data, and pass it to this view. Example: use the `Http` facade to request JSON/RSS and render titles, thumbnails, and links here.</p>
        </div>
    </section>

@endsection
