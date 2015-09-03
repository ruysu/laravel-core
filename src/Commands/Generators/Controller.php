<?php

namespace Ruysu\Core\Commands\Generators;

class Controller extends Generator
{

    /**
     * The command definition
     * @var string
     */
    protected $signature =
    'generate:controller {controller_name : The controller classname}' .
    ' {--template= : Change the template} {--dest= : Change the destination path}' .
    ' {--namespace= : The class namespace} {--model_namespace=App : The model namespace}' .
    ' {--nested= : Arguments passed to every method}' .
    ' {--form_request= : Store form request}' .
    ' {--ajax : Whether to use json responses}' .
    ' {--update_form_request= : Update form request}';

    /**
     * Get the destination path for our generated class
     * @return string
     */
    protected function getDestinationPath()
    {
        return app_path('Http/Controllers');
    }

    /**
     * Get the template data
     * @return array
     */
    protected function getTemplateData()
    {
        // The argument from which to begin
        $argument = $this->argument('controller_name');

        // Class definition
        $classname = $this->getBasename($argument);
        $namespace = implode('\\', array_filter([
            $this->option('namespace') ?: 'App\Http\Controllers',
            $this->getNamespace($argument),
        ]));

        // Resource definition
        $resource = str_singular(snake_case(preg_replace('/Controller$/', '', $classname)));
        $collection = str_plural($resource);
        $model = studly_case($resource);
        $model_namespace = $this->option('model_namespace', 'App');

        // Additional resources
        $nested = $this->option('nested');
        $use = "use {$model_namespace}\\{$model};";

        // method parameters
        $form_request = $this->option('form_request');
        $update_form_request = $this->option('update_form_request') ?: $form_request;
        $single_params = [];
        $resource_params = ['$id'];
        $store_params = [];
        $update_params = array_merge($resource_params, []);

        if ($nested) {
            $single_params = array_merge([$nested], $single_params);
            $resource_params = array_merge([$nested], $resource_params);
            $store_params = array_merge([$nested], $store_params);
            $update_params = array_merge([$nested], $update_params);
        }

        if ($form_request) {
            $store_params = array_merge($store_params, ["{$form_request} \$request"]);
        } else {
            $store_params = array_merge($store_params, ['Request $request']);
        }

        if ($update_form_request) {
            $update_params = array_merge($update_params, ["{$update_form_request} \$request"]);
        } else {
            $update_params = array_merge($update_params, ['Request $request']);
        }

        $single_params = implode(', ', $single_params);
        $store_params = implode(', ', $store_params);
        $update_params = implode(', ', $update_params);
        $resource_params = implode(', ', $resource_params);

        return compact(
            'namespace', 'classname', 'resource', 'collection', 'model', 'use',
            'nested', 'single_params', 'resource_params', 'store_params',
            'update_params'
        );

    }

    /**
     * Get the template file path for our generated class
     * @return string
     */
    protected function getTemplateFile()
    {
        if ($this->option('ajax')) {
            return __DIR__ . '/stubs/ApiController.txt';
        } else {
            return __DIR__ . '/stubs/Controller.txt';
        }
    }

    /**
     * Get the file path for our generated class
     * @return string
     */
    protected function getFilePath()
    {
        $argument = $this->argument('controller_name');
        $dest = $this->getFromOptionOrMethod('dest', 'getDestinationPath');
        $basename = $this->getBasename($argument);
        $dirname = $this->getDirname($argument);
        $parts = [$dest, $dirname, $basename . '.php'];
        return implode('/', array_filter($parts));
    }

}
