<?php

namespace Polaris\Web;

use App\PluginBase;
use Illuminate\Routing\Router;
use Polaris\Web\Http\Middleware\AutoLocale;

class WebServiceProvider extends PluginBase {

    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/Config/web.config.php', 'web.config');
        $this->mergeConfigFrom(__DIR__ . '/Config/metadata.web.php', 'metadata.web');
        $this->mergeConfigFrom(__DIR__ . '/Config/web.sidebar.user.php', 'web.sidebar.user');
        $this->mergeConfigFrom(__DIR__ . '/Config/web.sidebar.admin.php', 'web.sidebar.admin');
    }

    public function boot(Router $router) {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'web');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'web');
        $this->registerViewComposers();
        $this->registerMiddleware($router);

        $this->publishes([
            __DIR__.'/Config/web.config.php' => config_path('web.config.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/vendor/web'),
        ], 'translations');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/web'),
        ], 'views');

        $this->publishes([
            __DIR__.'/resources/assets' => public_path('vendor/web'),
        ], 'public');
    }

    public function registerViewComposers() {

    }

    public function registerMiddleware(Router $router) {
        $router->pushMiddlewareToGroup('web', AutoLocale::class);
    }

    public function getName(): string {
        return config('metadata.web.name');
    }

    public function getVersion(): string {
        return config('metadata.web.version');
    }

    public function getPackagistVendorName(): string {
        return config('metadata.web.vendor_name');
    }

    public function getPackagistPackageName(): string {
        return config('metadata.web.package_name');
    }

    public function getPackageRepositoryUrl(): string {
        return config('metadata.web.repository_url');
    }
}
