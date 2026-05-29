<?php

namespace Tempest\ResponsiveImage;

final readonly class ResponsiveImageConfig
{
    public string $srcPath;
    public string $publicPath;
    public bool $async;
    public bool $cache;

    public function __construct(
        string $srcPath,
        string $publicPath,
        bool $async = true,
        bool $cache = true,
    ) {
        $this->srcPath = realpath($srcPath) ?: $srcPath;
        $this->publicPath = realpath($publicPath) ?: $publicPath;
        $this->async = $async && class_exists('Tempest\CommandBus\CommandHandler');
        $this->cache = $cache;
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
