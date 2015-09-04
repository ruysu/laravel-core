<?php

namespace Ruysu\Core\Services;

use Illuminate\Http\Request;

class FileUploader
{

    /**
     * Laravel's request
     * @var Request
     */
    protected $request;

    /**
     * Array of files to move
     * @var array
     */
    protected $files = array();

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Upload an image and store its path to an entity key
     * @param  object       $entity
     * @param  string       $key
     * @param  string|null  $path
     * @return void
     */
    public function add($entity, $key, $path = null)
    {
        if (
            $this->request->hasFile($key) &&
            ($file = $this->request->file($key)) &&
            $file->isValid()
        ) {
            $source = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();
            $path = $path ?: public_path('uploads');
            $filename = microtime(true) . ".{$extension}";
            $entity->$key = $filename;

            $this->files[$source] = compact('path', 'filename');

            return $job;
        }

        return false;
    }

    /**
     * Handle all files
     * @return void
     */
    public function upload()
    {
        foreach ($this->files as $source => $dest) {
            $file = new File($source);
            $file->move($dest['path'], $dest['filename']);
        }
    }

}
