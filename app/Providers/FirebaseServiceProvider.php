<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('firebase', function ($app) {
            $serviceAccountPath = storage_path('firebase_credentials.json');
            return (new Factory)
                ->withServiceAccount($serviceAccountPath)
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
                ->create();
        });
    }
}

