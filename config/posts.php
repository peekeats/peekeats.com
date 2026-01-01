<?php

return [
    // Enable or disable the posts feature. Controlled via env `POSTS_ENABLED`.
    'enabled' => env('POSTS_ENABLED', true),

    // Model used for posts. Set via env `POSTS_MODEL` to override.
    // Example in .env: POSTS_MODEL=App\\Models\\WpPost
    'model' => env('POSTS_MODEL', App\Models\WpPost::class),
];
