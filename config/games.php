<?php

return [
    // Enable or disable the Games feature.
    'enabled' => env('GAMES_ENABLED', true),

    // Curated list of games to show in the Games theme.
    // Each entry: title, slug (optional), description, url (optional), thumbnail (optional).
    'list' => [
        [
            'title' => 'Space Runner',
            'slug' => 'space-runner',
            'description' => 'A fast-paced endless runner set among asteroids and drifting satellites.',
            'url' => 'https://nikniq.com/space-runner',
        ],
        [
            'title' => 'Puzzle Grove',
            'slug' => 'puzzle-grove',
            'description' => 'Relaxing puzzle challenges with handcrafted levels and soothing visuals.',
            'url' => 'https://nikniq.com/puzzle-grove',
        ],
        [
            'title' => 'Neon Racers',
            'slug' => 'neon-racers',
            'description' => 'Retro-style top-down racing with tight controls and online leaderboards.',
            'url' => 'https://nikniq.com/neon-racers',
        ],
    ],
];
