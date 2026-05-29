<?php

namespace Tempest\ResponsiveImage;

final class SrcSet
{
    public function __construct(
        public string $src,
        public int $width,
        public int $height,
    ) {}

    public function __toString(): string
    {
        return "{$this->src} {$this->width}w";
    }
}
