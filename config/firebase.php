<?php
return [
    'enabled' => env('FIREBASE_ENABLED', false),
    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase.json')),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', env('FIREBASE_PROJECT_ID') ? env('FIREBASE_PROJECT_ID').'.firebasestorage.app' : null),
    'project_id' => env('FIREBASE_PROJECT_ID', 'anproject-8968f'),
    'database_url' => env('FIREBASE_DATABASE_URL'),
    // Konfigurasi Web SDK (dibaca frontend via VITE_FIREBASE_*).
    'web' => [
        'api_key' => env('VITE_FIREBASE_API_KEY'),
        'auth_domain' => env('VITE_FIREBASE_AUTH_DOMAIN'),
        'messaging_sender_id' => env('VITE_FIREBASE_MESSAGING_SENDER_ID'),
        'app_id' => env('VITE_FIREBASE_APP_ID'),
    ],
];
