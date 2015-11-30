<?php

namespace Ruysu\Core\Services;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Ruysu\Core\Jobs\ResizeImage;

class ImageUploader
{

    use DispatchesJobs;

    /**
     * Laravel's request
     * @var Request
     */
    protected $request;

    /**
     * Array of jobs to process
     * @var array
     */
    protected $jobs = array();

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
            $extension = $file->getClientOriginalExtension();
            $name = basename($file->getRealPath());
            $file->move($source = storage_path('app/catalog'));
            $path = $path ?: public_path('uploads');
            $filename = microtime(true) . ".{$extension}";
            $entity->$key = $filename;

            $job = new ResizeImage("{$source}/{$name}", $path, $filename);
            $this->jobs[] = $job;

            return $job;
        }

        return false;
    }

    /**
     * Handle all jobs
     * @return void
     */
    public function upload()
    {
        foreach ($this->jobs as $job) {
            $this->dispatch($job);
        }
    }

}
