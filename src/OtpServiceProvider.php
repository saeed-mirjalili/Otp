<?php

namespace Saeed\Otp;
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
        require __DIR__ . '/Http/routes.php';

        $this->publishes([
            __DIR__.'/Config/OtpConf.php' => config_path('otp.php'),
            __DIR__.'/Views' => base_path('resources/views/otp'),
            __DIR__.'/Migrations' => database_path('/migrations'),
            __DIR__.'/Public/css' => public_path('/css'),
            __DIR__.'/Public/img' => public_path('/img'),
        ],'otp');

        // مسیر viewهای publish شده
        $publishedViewsPath = base_path('resources/views/otp');

        // اگر مسیر publish شده وجود داشت، آن را به عنوان اولین مسیر view ثبت کن
        if (is_dir($publishedViewsPath)) {
            $this->loadViewsFrom($publishedViewsPath, 'otp');
        }

        // ثبت مسیر viewهای پکیج
        $this->loadViewsFrom(__DIR__.'/Views', 'otp');
    }

}
