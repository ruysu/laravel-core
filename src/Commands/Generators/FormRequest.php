<?php

namespace Ruysu\Core\Commands\Generators;

class FormRequest extends Generator
{

    /**
     * The command definition
     * @var string
     */
    protected $signature =
    'generate:request {request_name : The request classname}' .
    ' {--template= : Change the template} {--dest= : Change the destination path}' .
    ' {--namespace= : The class namespace} {--ajax : Whether the request is ajax}';

    /**
     * Get the destination path for our generated class
     * @return string
     */
    protected function getDestinationPath()
    {
        return app_path('Http/Requests');
    }

    /**
     * Get the template data
     * @return array
     */
    protected function getTemplateData()
    {
        // The argument from which to begin
        $argument = $this->argument('request_name');

        // Class definition
        $classname = $this->getBasename($argument);
        $namespace = implode('\\', array_filter([
            $this->option('namespace') ?: 'App\Http\Requests',
            $this->getNamespace($argument),
        ]));
        $baseclass = $this->option('ajax') ? 'ApiFormRequest' : 'FormRequest';

        return compact(
            'namespace', 'classname', 'baseclass'
        );
    }

    /**
     * Get the template file path for our generated class
     * @return string
     */
    protected function getTemplateFile()
    {
        return __DIR__ . '/stubs/FormRequest.txt';
    }

    /**
     * Get the file path for our generated class
     * @return string
     */
    protected function getFilePath()
    {
        $argument = $this->argument('request_name');
        $dest = $this->getFromOptionOrMethod('dest', 'getDestinationPath');
        $basename = $this->getBasename($argument);
        $dirname = $this->getDirname($argument);
        $parts = [$dest, $dirname, $basename . '.php'];
        return implode('/', array_filter($parts));
    }

}
