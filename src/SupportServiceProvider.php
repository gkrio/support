<?php
namespace Gkr\Support;


use Gkr\Support\Config\ConfigServiceProvider;
use Gkr\Support\Markdown\MarkdownServiceProvider;
use Gkr\Support\Restful\RestfulServiceProvider;
use Gkr\Support\Stub\StubServiceProvider;
use Gkr\Support\Translation\TranslationServiceProvider;
use Gkr\Support\View\ViewServiceProvider;
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
        $this->app->register(TranslationServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(MarkdownServiceProvider::class);
        $this->app->register(RestfulServiceProvider::class);
    }
}