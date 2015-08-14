<?php

namespace Ruysu\Core\Providers;

use Ruysu\Core\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
        $this->setupMigrations();
        $this->setupViews();
    }

    /**
     * Setup the config.
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../../config/core.php');
        $this->publishes([$source => config_path('core.php')], 'config');
        $this->mergeConfigFrom($source, 'core');
    }

    /**
     * Setup the migrations.
     * @return void
     */
    protected function setupMigrations()
    {
        $source = realpath(__DIR__ . '/../../database/migrations/');
        $this->publishes([$source => database_path('migrations')], 'migrations');
    }

    protected function setupLang()
    {
        $source = realpath(__DIR___ . '/../../lang');

        if (is_dir($path = base_path('/resources/lang/packages/' . PACKAGE_NAME))) {
            $this->loadTranslationsFrom($path, 'core');
        } else {
            $this->loadTranslationsFrom($source, 'core');
        }

        $this->publishes([$source => $path], 'lang');
    }

    /**
     * Setup the views
     * @return void
     */
    protected function setupViews()
    {
        $source = realpath(__DIR___ . '/../../resources/views');

        if (is_dir($path = base_path('/resources/views/packages/' . PACKAGE_NAME))) {
            $this->loadViewsFrom($path, 'core');
        } else {
            $this->loadViewsFrom($source, 'core');
        }

        $this->publishes([$source => $path], 'views');
    }

}
