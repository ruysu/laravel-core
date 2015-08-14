<?php

namespace Ruysu\Core\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Intervention\Image\ImageManager;

class ImageUploader
{

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Image processor
     * @var ImageManager
     */
    protected $image;

    /**
     * Queue driver
     * @var Queue
     */
    protected $queue;

    /**
     * Array utilities
     * @var Arr
     */
    protected $utils;

    /**
     * Laravel's request
     * @var Request
     */
    protected $request;

    /**
     * @param Filesystem   $filesystem
     * @param Request      $request
     * @param ImageManager $image
     * @param Queue $queue
     * @param Arr          $utils
     */
    public function __construct(
        Filesystem $filesystem,
        Request $request,
        ImageManager $image,
        Queue $queue,
        Arr $utils
    ) {
        $this->filesystem = $filesystem;
        $this->request = $request;
        $this->image = $image;
        $this->queue = $queue;
        $this->utils = $utils;
    }

    /**
     * Upload an image and store its path to an entity key
     * @param  object       $entity
     * @param  string       $key
     * @param  array        $sizes
     * @param  string|null  $path
     * @return void
     */
    public function upload($entity, $key, array $sizes, $path = null)
    {
        if ($this->request->hasFile($key) && ($file = $this->request->file($key)) && $file->isValid()) {
            $path = $path ?: public_path('uploads');
            $this->filesystem->makeDirectory($path, 0755, true, true);

            $extension = $file->getClientOriginalExtension();
            $filename = microtime(true) . ".{$extension}";
            $source = $file->getRealPath();

            $this->queue->push(__CLASS__ . '@queueUpload', compact('source', 'extension', 'filename', 'path', 'sizes'));

            $entity->$key = $filename;
        }
    }

    /**
     * Delegate the image processing
     * @param  object $job
     * @param  array  $data
     * @return void
     */
    public function queueUpload($job, array $data)
    {
        $job->delete();

        extract($data);

        $this->uploadAndResize($source, $extension, $filename, $path, $sizes);
    }

    /**
     * Perform the resizing of an image
     * @param  string $source
     * @param  string $extension
     * @param  string $filename
     * @param  string $path
     * @param  array  $sizes
     * @return void
     */
    public function uploadAndResize($source, $extension, $filename, $path, $sizes)
    {
        $image = $this->image->make($source);
        $image->backup();

        foreach ($sizes as $size => $dimensions) {
            $size = $size ? "{$size}_" : '';
            $dest = "{$path}/{$size}{$filename}";

            $width = $this->utils->get($dimensions, 'width');
            $height = $this->utils->get($dimensions, 'height');
            $padding = $this->utils->get($dimensions, 'padding', 0) * 2;

            if (!$height) {
                $image->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($dest);
            } elseif (!$width) {
                $image->resize(null, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($dest);
            } else {
                if ($padding) {
                    $image->resize($width - $padding, $height - $padding, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                } else {
                    $image->fit($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                $this->image->canvas($width, $height, '#ffffff')
                    ->insert($image, 'center')
                    ->save($dest);
            }

            $image->reset();
        }

        $image->destroy();
    }

}
