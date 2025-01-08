<?php

namespace saeed\otp;
use \Illuminate\Support\ServiceProvider;
class OtpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('otp', function (){
            return new OtpService;
        });

        $this->mergeConfigFrom(__DIR__.'/Config/OtpConf.php', 'otp');
    }

    public function boot()
    {
        require __DIR__ . '\Http\routes.php';

        $this->loadViewsFrom(__DIR__.'/Views','otp');

        $this->publishes([
            __DIR__.'/Config/OtpConf.php' => config_path('otp.php'),
            __DIR__.'/Views' => base_path('resources/views/otp'),
            __DIR__.'/Migrations' => database_path('/migrations'),
            __DIR__.'/Public/css' => public_path('/css'),
            __DIR__.'/Public/img' => public_path('/img'),
        ],'otp');

//        $this->publishes([],'views');
    }
}
