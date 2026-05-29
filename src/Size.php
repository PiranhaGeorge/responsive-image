<?php

namespace Tempest\ResponsiveImage;

final readonly class Size
{
    public function __construct(
        public int $maxWidth,
        public int $width,
    ) {}

    public function __toString(): string
    {
        return "(max-width: {$this->maxWidth}px) {$this->width}px";
    }
}
