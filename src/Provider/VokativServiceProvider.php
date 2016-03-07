<?php

namespace Avram\Vokativ\Provider;

use Avram\Vokativ\Dictionary\VokativIniDictionary;
use Avram\Vokativ\Vokativ;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class VokativServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('vokativ', function (Application $app) {
            $dictionary = is_file(storage_path('avram/vokativ/vokativ.ini')) ? storage_path('avram/vokativ/vokativ.ini') : null;
            return new Vokativ(new VokativIniDictionary($dictionary));
        });

        $this->publishes([
           realpath( __DIR__ . ('/../Data')) => storage_path('avram/vokativ'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
