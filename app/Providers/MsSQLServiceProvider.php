<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\MsSQLGeneric;

class MsSQLServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * This service is used for providing connection of MS SQL Connection integrated with Sutlej data connections.
     * @param Custom service class used to bind with.
     * @return void
     * @method BIND
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\MsSQLGeneric', function ($app) {
            return new MsSQLGeneric();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
