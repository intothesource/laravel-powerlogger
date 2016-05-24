<?php

namespace IntoTheSource\Powerlogger;

use Illuminate\Support\ServiceProvider;

class PowerloggerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // this for config
        $this->publishes([
            __DIR__.'/config/powerlogger.php' => config_path('powerlogger.php'),
        ]);

    }
    
    /**
     * Registers the config file during publishing.
     *
     * @return void
     */
    public function register()
    {
        config([
            'powerlogger' => include('config/powerlogger.php'),
        ]);
    }
}