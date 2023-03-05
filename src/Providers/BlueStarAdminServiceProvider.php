<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

namespace BlueStar\Providers;

use BlueStar\BlueStarAdmin;
use BlueStar\Contracts\ModuleRepositoryInterface;
use BlueStar\Support\DB\Query;
use BlueStar\Support\Module\ModuleManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use BlueStar\Support\Macros\MacrosRegister;

/**
 * BlueStarAmin Service Provider
 */
class BlueStarAdminServiceProvider extends ServiceProvider
{
    /**
     * boot
     *
     * @return void
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function boot(): void
    {
        $this->bootDefaultModuleProviders();
        $this->bootModuleProviders();
        $this->registerEvents();
        $this->listenDBLog();
        $this->app->make(MacrosRegister::class)->boot();
    }

    /**
     * register
     *
     * @return void
     * @throws ReflectionException
     */
    public function register(): void
    {
        $this->registerCommands();

        $this->registerModuleRepository();

        $this->publishConfig();

        $this->publishModuleMigration();
    }


    /**
     * register commands
     *
     * @return void
     * @throws ReflectionException
     */
    protected function registerCommands(): void
    {
        loadCommands(dirname(__DIR__).DIRECTORY_SEPARATOR.'Commands', 'BlueStar\\');
    }

    /**
     * bind module repository
     *
     * @return void
     */
    protected function registerModuleRepository(): void
    {
        // register module manager
        $this->app->singleton(ModuleManager::class, function () {
            return new ModuleManager(fn () => Container::getInstance());
        });

        // register module repository
        $this->app->singleton(ModuleRepositoryInterface::class, function () {
            return $this->app->make(ModuleManager::class)->driver();
        });

        $this->app->alias(ModuleRepositoryInterface::class, 'module');
    }

    /**
     * register events
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        Event::listen(RequestHandled::class, config('bluestar.response.request_handled_listener'));
    }

    /**
     * publish config
     *
     * @return void
     */
    protected function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $from = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bluestar.php';

            $to = config_path('bluestar.php');

            $this->publishes([$from => $to], 'bluestar-config');
        }
    }


    /**
     * publish module migration
     *
     * @return void
     */
    protected function publishModuleMigration(): void
    {
        if ($this->app->runningInConsole()) {
            $form = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'2022_11_14_034127_module.php';

            $to = database_path('migrations').DIRECTORY_SEPARATOR.'2022_11_14_034127_module.php';

            $this->publishes([$form => $to], 'bluestar-module');
        }
    }

    /**
     *
     * @return void
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    protected function bootDefaultModuleProviders(): void
    {
        foreach ($this->app['config']->get('bluestar.module.default', []) as $module) {
            $provider = BlueStarAdmin::getModuleServiceProvider($module);
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    /**
     * boot module
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function bootModuleProviders()
    {
        foreach ($this->app->make(ModuleRepositoryInterface::class)->getEnabled() as $module) {
            if (class_exists($module['provider'])) {
                $this->app->register($module['provider']);
            }
        }

        $this->registerModuleRoutes();
    }

    /**
     * register module routes
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function registerModuleRoutes()
    {
        if (! $this->app->routesAreCached()) {
            $route = $this->app['config']->get('bluestar.route');

            if (! empty($route)) {
                Route::prefix($route['prefix'])
                    ->middleware($route['middlewares'])
                    ->group($this->app['config']->get('bluestar.module.routes'));
            }
        }
    }

    /**
     * listen db log
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return void
     */
    protected function listenDBLog(): void
    {
        if ($this->app['config']->get('bluestar.listen_db_log')) {
            Query::listen();

            $this->app->terminating(function () {
                Query::log();
            });
        }
    }

    /**
     * file exist
     *
     * @return bool
     */
    protected function routesAreCached(): bool
    {
        return $this->app->routesAreCached();
    }
}
