<?php

namespace Ruysu\Core;

use Ruysu\Core\Commands\Generators\Controller as GenerateController;
use Ruysu\Core\Commands\Generators\FormRequest as GenerateFormRequest;
use Ruysu\Core\Commands\Generators\Model as GenerateModel;
use Ruysu\Core\Commands\Generators\Views as GenerateViews;
use Ruysu\Core\Commands\OAuth\CreateClient;
use Ruysu\Core\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    /**
     * Register this service provider
     * @return void
     */
    public function register()
    {
        $this->app->register('LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider');
        $this->app->register('LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider');
        $this->app->register('Intervention\Image\ImageServiceProvider');
        $this->app->register('anlutro\LaravelSettings\ServiceProvider');
    }

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

    /**
     * Add commands to artisan
     * @return void
     */
    protected function setupCommands()
    {
        $this->commands([
            GenerateController::class,
            GenerateFormRequest::class,
            GenerateModel::class,
            GenerateViews::class,
            CreateClient::class,
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

    /**
     * Setup lang files
     * @return void
     */
    protected function setupLang()
    {
        $source = realpath(__DIR__ . '/../resources/lang');

        if (is_dir($path = base_path('/resources/lang/vendor/laravel-core'))) {
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

        // if views have been published, load from published directory
        if (is_dir($path = base_path('/resources/views/vendor/laravel-core'))) {
            $this->loadViewsFrom($path, 'core');
        } else {
            $this->loadViewsFrom($source, 'core');
        }

        $this->publishes([$source => $path], 'views');
    }

}
