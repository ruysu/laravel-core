<?php

namespace Ruysu\Core\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Intervention\Image\ImageManager;

class ResizeImage extends Job implements SelfHandling, ShouldQueue
{

    /**
     * The sizes to resize the image to
     * @var Collection
     */
    protected $sizes;

    /**
     * The filename
     * @var The target filename
     */
    protected $filename;

    /**
     * The source file
     * @var resource
     */
    protected $source;

    /**
     * Path to save the image to
     * @var string
     */
    protected $path;

    /**
     * Quality to save the image with
     * @var string
     */
    protected $quality = 70;

    /**
     * @param string $source
     * @param string $path
     * @param string $filename
     */
    public function __construct($source, $path, $filename)
    {
        $this->sizes = new Collection;
        $this->path = $path;
        $this->source = $source;
        $this->filename = $filename;
    }

    /**
     * Path to save the image to
     * @param  string $path
     * @return string|ResizeImage
     */
    public function path($path = null)
    {
        if (is_null($path)) {
            return $this->path;
        }

        $this->path = $path;

        return $this;
    }

    /**
     * Filename to save the image as
     * @param  string $filename
     * @return string|ResizeImage
     */
    public function filename($filename = null)
    {
        if (is_null($filename)) {
            return $this->filename;
        }

        $this->filename = $filename;

        return $this;
    }

    /**
     * Quality to save the image with
     * @param  string $quality
     * @return string|ResizeImage
     */
    public function quality($quality = null)
    {
        if (is_null($quality)) {
            return $this->quality;
        }

        $this->quality = $quality;

        return $this;
    }

    /**
     * Add a size to the resizer
     * @param string  $size
     * @param integer $width
     * @param integer $height
     * @param integer $padding
     */
    public function addSize($size, $width = null, $height = null, $padding = 0)
    {
        $this->sizes->put($size, new Fluent(compact('width', 'height', 'padding')));
        return $this;
    }

    /**
     * Shortcut to generate a square thumbnail
     * @param string  $size
     * @param integer $side
     * @param integer $padding
     */
    public function addSquare($size, $side, $padding = 0)
    {
        return $this->addSize($size, $side, $side, $padding);
    }

    /**
     * Save and resize the image
     * @param  ImageManager $resizer
     * @return void
     */
    public function handle(ImageManager $resizer, Filesystem $filesystem)
    {
        $filesystem->makeDirectory($this->path, 0755, true, true);

        $image = $resizer->make($this->source);
        $image->backup();

        $this->sizes->each(function ($dims, $size) use ($resizer, $image) {
            $size = !$size || $size === 'default' ? '' : "{$size}_";
            $dest = "{$this->path}/{$size}{$this->filename}";

            if ($dims->width && !$dims->height) {
                $image->resize($dims->width, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($dest, $this->quality);
            } else if ($dims->height && !$dims->width) {
                $image->resize(null, $dims->height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($dest, $this->quality);
            } else {
                if ($dims->padding) {
                    $image->resize($dims->width - $dims->padding, $dims->height - $dims->padding, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                } else {
                    $image->fit($dims->width, $dims->height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                $resizer->canvas($dims->width, $dims->height, '#ffffff')
                    ->insert($image, 'center')
                    ->save($dest, $this->quality);
            }

            $image->reset();
        });

        $image->destroy();
    }

}
