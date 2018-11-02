<?php namespace Crocodic\LaravelDBPacker;

use Crocodic\LaravelDBPacker\Commands\Pack;
use Crocodic\LaravelDBPacker\Commands\Unpack;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use App;

class LaravelDBPackerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->register('Way\Generators\GeneratorsServiceProvider');
        $this->app->register('Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider');

        $this->app->singleton('LaravelDBPacker', function ()
        {
            return true;
        });

        $this->commands([
            Pack::class,
            Unpack::class
        ]);

    }
}