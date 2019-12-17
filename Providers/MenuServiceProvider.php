<?php

namespace Pingu\Menu\Providers;

use Asset;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\Router;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Menu\Http\Middleware\DeletableMenu;
use Pingu\Menu\Http\Middleware\DeletableMenuItem;
use Pingu\Menu\Menus;

class MenuServiceProvider extends ModuleServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $entities = [
        Menu::class,
        MenuItem::class
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'menu');
        $this->registerFactories();
        Asset::container('modules')->add('menu-js', 'module-assets/Menu.js');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('menu.menus', Menus::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->registerEntities($this->entities);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'menu'
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/menu');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'menu');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'menu');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
