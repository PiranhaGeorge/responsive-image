<?php

namespace Tempest\ResponsiveImage;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

final class ResponsiveImageConfig
{
    public function __construct(
        /** @param string $srcPath The path to the directory where all source images are stored. */
        public string $srcPath,
        /** @param string $publicPath The path to the public directory where rendered images should be served from. */
        public string $publicPath,
        /** @param bool $async Whether responsive images should be generated in the background. This paramater is only taken into account if tempest/command-bus is installed. */
        public bool $async = true,
        /** @param bool $cache Whether generated responsive variants should be cached. If true, then responsive variants won't be generated as long as the main image file exists in the public path. Cache clearing should be done manually on your end. */
        public bool $cache = true,
        /** @param ImageManager $imageManager The Intervention imagemanager. Refer to the [Intervention docs](https://image.intervention.io/v4) for all options. */
        public ImageManager $imageManager = new ImageManager(new Driver()),
    ) {
        $this->srcPath = realpath($srcPath) ?: $this->srcPath;
        $this->publicPath = realpath($publicPath) ?: $this->publicPath;
        $this->async = $this->async && class_exists('Tempest\CommandBus\CommandHandler');
    }

    public function makeSrcPath(string $src): string
    {
        return rtrim($this->srcPath, '/') . '/' . ltrim($src, '/');
    }

    public function makePublicPath(string $src): string
    {
        return rtrim($this->publicPath, '/') . '/' . ltrim($src, '/');
    }
}
