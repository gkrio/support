<?php
namespace Gkr\Support\Config;


use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('gkr.support.config',Config::class);
        $this->app->alias(Config::class,'gkr.support.config');
    }
}