<?php

namespace Tempest\ResponsiveImage;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

final class ResponsiveImageConfig
{
    public function __construct(
        public string $srcPath,
        public string $publicPath,
        public bool $async = true,
        public bool $cache = true,
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
