<?php

namespace Ruysu\Core\Commands\Generators;

class Model extends Generator
{

    /**
     * The command definition
     * @var string
     */
    protected $signature =
    'generate:model {model_name : The model classname}' .
    ' {--template= : Change the template} {--dest= : Change the destination path}' .
    ' {--namespace= : The class namespace}' .
    ' {--table= : Table used by the model}' .
    ' {--fillable= : Fillable attributes, comma separated}' .
    ' {--dates= : Date attributes, comma separated}' .
    ' {--hidden= : Hidden attributes, comma separated}';

    /**
     * Get the destination path for our generated class
     * @return string
     */
    protected function getDestinationPath()
    {
        return app_path();
    }

    /**
     * Get the template data
     * @return array
     */
    protected function getTemplateData()
    {
        // The argument from which to begin
        $argument = $this->argument('model_name');

        // Class definition
        $classname = $this->getBasename($argument);
        $namespace = implode('\\', array_filter([
            $this->option('namespace') ?: 'App',
            $this->getNamespace($argument),
        ]));

        $attributes = ['fillable', 'dates', 'hidden'];

        foreach ($attributes as $collection) {
            $$collection = array_map(
                function ($val) {
                    $val = trim($val);
                    return "'{$val}'";
                },
                array_filter(explode(',', $this->option($collection)))
            );
            $$collection = implode(', ', $$collection);
        }

        $table = $this->option('table') ?: str_plural(snake_case($classname));

        return compact(
            'namespace', 'classname', 'fillable', 'dates', 'hidden', 'table'
        );
    }

    /**
     * Get the template file path for our generated class
     * @return string
     */
    protected function getTemplateFile()
    {
        return __DIR__ . '/stubs/Model.txt';
    }

    /**
     * Get the file path for our generated class
     * @return string
     */
    protected function getFilePath()
    {
        $argument = $this->argument('model_name');
        $dest = $this->getFromOptionOrMethod('dest', 'getDestinationPath');
        $basename = $this->getBasename($argument);
        $dirname = $this->getDirname($argument);
        $parts = [$dest, $dirname, $basename . '.php'];
        return implode('/', array_filter($parts));
    }

}
