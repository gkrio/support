<?php
namespace Gkr\Support\Markdown;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class MarkdownServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('markdown', function ($content) {
            return "<?php echo markdown($content) ?>";
        });
    }
    public function register()
    {
        $this->app->singleton('gkr.markdown',Markdown::class);
        $this->app->alias('gkr.markdown',MarkdownContract::class);
    }
}