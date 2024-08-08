<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\LaravelCache;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the default configuration
        $this->mergeConfigFrom(
            __DIR__.'/../../config/botman.php', 'botman'
        );
    }

    public function boot()
    {
        DriverManager::loadDriver(config('botman.matching.driver'));

        $config = config('botman');
        
        $this->app->singleton('botman', function ($app) use ($config) {
            return BotManFactory::create($config, new LaravelCache());
        });
    }
}
