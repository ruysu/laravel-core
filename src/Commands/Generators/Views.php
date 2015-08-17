<?php

namespace Ruysu\Core\Commands\Generators;

use Illuminate\Console\Command;

class Views extends Command
{

    /**
     * The command definition
     * @var string
     */
    protected $signature =
    'generate:views {resource : Resource name}' .
    ' {--dest= : Change the destination path}';

    /**
     * Fire the command
     * @return void
     */
    public function fire()
    {
        $dest = $this->getFromOptionOrMethod('dest', 'getDestinationPath');
        $actions = ['show', 'edit', 'create', 'index', '_form'];
        $resource = str_plural(snake_case($this->argument('resource')));

        $base_path = base_path("resources/views/{$resource}");

        if (!is_dir($base_path)) {
            mkdir($base_path, 0755, true);
        }

        foreach ($actions as $action) {
            $path = "{$base_path}/{$action}.blade.php";
            file_put_contents($path, $path);
        }

        $this->info("Views for {$resource} created in {$base_path}");
    }

    /**
     * Get a value from an option provided to the command
     * or from a method defined in the command
     * @param  string $option
     * @param  string $method
     * @return mixed
     */
    protected function getFromOptionOrMethod($option, $method)
    {
        if ($value = $this->option($option)) {
            return $value;
        }

        return is_callable([$this, $method]) ? $this->$method() : $method;
    }

}
