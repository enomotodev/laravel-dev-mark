<?php

namespace LaravelDevMark;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LaravelDevMark\Middleware\InjectDevMark;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/laravel-dev-mark.php';
        $this->mergeConfigFrom($configPath, 'laravel-dev-mark');

        $this->app->singleton(LaravelDevMark::class, function () {
            return new LaravelDevMark($this->app);
        });
    }

    /**
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/laravel-dev-mark.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $this->registerMiddleware();
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('laravel-dev-mark.php');
    }

    /**
     * @return void
     */
    protected function registerMiddleware()
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware(InjectDevMark::class);
    }
}
