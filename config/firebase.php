<?php

return [
    'credentials_file' => env('FIREBASE_CREDENTIALS', storage_path('firebase/firebase_credentials.json')),
    'database_url' => env('FIREBASE_DATABASE_URL', 'https://fee-cancel-default-rtdb.firebaseio.com/'),
];
