<?php
namespace Gkr\Support;


use Gkr\Support\Config\ConfigServiceProvider;
use Gkr\Support\Stub\StubServiceProvider;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('gkr.support',function($app){
            return true;
        });
        $this->registerComponents();
    }

    public function registerComponents()
    {
        $this->app->register(ConfigServiceProvider::class);
        $this->app->register(StubServiceProvider::class);
    }
}