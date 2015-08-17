<?php

namespace Ruysu\Core\Commands\Generators;

use Illuminate\Console\Command;

abstract class Generator extends Command
{

    /**
     * Get the destination path for our generated class
     * @return string
     */
    abstract protected function getDestinationPath();

    /**
     * Get the template data
     * @return array
     */
    abstract protected function getTemplateData();

    /**
     * Get the template file path for our generated class
     * @return string
     */
    abstract protected function getTemplateFile();

    /**
     * Get the file path for our generated class
     * @return string
     */
    abstract protected function getFilePath();

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

    /**
     * Fire the command
     * @return void
     */
    public function fire()
    {
        $file_path = $this->getFilePath();

        // if (!file_exists($file_path)) {
        $this->generate(
            $this->getFromOptionOrMethod('template', 'getTemplateFile'),
            $this->getTemplateData(),
            $file_path
        );

        $this->info("Created: {$file_path}");
        // } else {
        // $this->error("The file, {$file_path}, already exists! I don't want to overwrite it.");
        // }
    }

    /**
     * Generate the file
     * @param  string $template_path
     * @param  array  $data
     * @param  string $file_path
     * @return void
     */
    protected function generate($template_path, $data, $file_path)
    {
        $template = file_get_contents($template_path);
        $dirname = dirname($file_path);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }

        file_put_contents(
            $file_path,
            $this->compile($template, $data)
        );
    }

    /**
     * Compile the template data into the template file
     * @param  string $template
     * @param  arrat $data
     * @return string
     */
    protected function compile($template, $data)
    {
        foreach ($data as $key => $value) {
            $template = preg_replace("/\\$$key\\$/i", $value, $template);
        }

        // $template = preg_replace("/\\$[^\\$]+\\$/i", '', $template);

        return $template;
    }

    /**
     * Get the basename of the argument passed
     * @param  string $argument
     * @return string
     */
    protected function getBasename($argument)
    {
        $controller_name = str_replace('\\', '/', $argument);
        $basename = basename($controller_name);
        return preg_replace('/\.php$/', '', $basename);
    }

    /**
     * Get the namespace of the argument passed
     * @param  string $argument
     * @return string
     */
    protected function getNamespace($argument)
    {
        return str_replace('/', '\\', $this->getDirname($argument));
    }

    /**
     * Get the dirname of the argument passed
     * @param  string $argument
     * @return string
     */
    protected function getDirname($argument)
    {
        $controller_name = str_replace('\\', '/', $argument);
        $dirname = dirname($controller_name);
        return trim($dirname, '.');
    }

}
