<?php

namespace Ruysu\Core;

use Ruysu\Core\Commands\Generators\Controller as GenerateController;
use Ruysu\Core\Commands\Generators\FormRequest as GenerateFormRequest;
use Ruysu\Core\Commands\Generators\Model as GenerateModel;
use Ruysu\Core\Commands\Generators\Views as GenerateViews;
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
        $this->setupCommands();
    }

    protected function setupCommands()
    {
        $this->commands([
            GenerateController::class,
            GenerateFormRequest::class,
            GenerateModel::class,
            GenerateViews::class,
        ]);
    }

    /**
     * Setup the config.
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/core.php');
        $this->publishes([$source => config_path('core.php')], 'config');
        $this->mergeConfigFrom($source, 'core');
    }

    /**
     * Setup the migrations.
     * @return void
     */
    protected function setupMigrations()
    {
        $source = realpath(__DIR__ . '/../database/migrations/');
        $this->publishes([$source => database_path('migrations')], 'migrations');
    }

    protected function setupLang()
    {
        $source = realpath(__DIR__ . '/../resources/lang');

        if (is_dir($path = base_path('/resources/lang/packages/ruysu/laravel-core'))) {
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
        $source = realpath(__DIR__ . '/../resources/views');

        if (is_dir($path = base_path('/resources/views/packages/ruysu/laravel-core'))) {
            $this->loadViewsFrom($path, 'core');
        } else {
            $this->loadViewsFrom($source, 'core');
        }

        $this->publishes([$source => $path], 'views');
    }

}
