@extends('layouts.app')

@section('title', 'Games')

@section('content')
    <header class="hero">
        <div class="card">
            <p class="eyebrow">Games</p>
            <h1>Games and Playlists</h1>
            <p class="lead">Curated games and related content. External content is linked from nikniq.com — visit the source for full descriptions and media.</p>
            <p style="margin-top:1rem;"><a href="https://nikniq.com" class="link" target="_blank" rel="noopener">Browse more on nikniq.com →</a></p>
        </div>
    </header>
    {{-- Curated games list from config/games.php --}}
    @if(!empty($games ?? []))
        <section style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
            @foreach($games as $game)
                <div class="card">
                    <h3>{{ $game['title'] }}</h3>
                    <p class="lead">{{ $game['description'] }}</p>
                    @if(!empty($game['url']))
                        <p><a class="link" href="{{ $game['url'] }}" target="_blank" rel="noopener">View on nikniq.com →</a></p>
                    @endif
                </div>
            @endforeach
        </section>
    @endif

    {{-- Assimilated feed items from nikniq.com --}}
    <section style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
        @if(!empty($items ?? []))
            @foreach($items as $item)
                <article class="card">
                    <h3>{{ $item['title'] }}</h3>
                    @if(!empty($item['date']))
                        <div style="color:var(--muted);font-size:0.95rem;margin-bottom:0.5rem;">{{ optional(\Illuminate\Support\Carbon::parse($item['date']))->format('M j, Y') }}</div>
                    @endif
                    <p class="lead">{{ $item['excerpt'] }}</p>
                    <p><a class="link" href="{{ $item['link'] }}" target="_blank" rel="noopener">Read on nikniq.com →</a></p>
                </article>
            @endforeach
        @else
            <div class="card">
                <h3>No items available</h3>
                <p class="lead">We couldn't fetch content from nikniq.com right now. Try again later.</p>
                <p><a class="link" href="https://nikniq.com" target="_blank" rel="noopener">Browse nikniq.com</a></p>
            </div>
        @endif
    </section>

    <section style="margin-top:1.5rem;">
        <div class="card">
            <h2>Embedding external content</h2>
            <p class="lead">To surface content from nikniq.com without copying copyrighted text, fetch their feed or API in a controller, transform data, and pass it to this view. Example: use Guzzle to request JSON/RSS and render titles, thumbnails, and links here.</p>
        </div>
    </section>
@endsection
