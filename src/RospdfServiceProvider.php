<?php

namespace Vieira\Rospdf;

use Illuminate\Support\ServiceProvider;

class RospdfServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/rospdf.php' => config_path('rospdf.php'),
        ]);
    }


    public function register()
    {
        $this->app['Rospdf'] = $this->app->share(function ($app) {
            return new Rospdf();
        });
    }
}
