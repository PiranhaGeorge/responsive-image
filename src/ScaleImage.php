<?php

namespace Tempest\ResponsiveImage;

use Tempest\CommandBus\Async;

#[Async]
final readonly class ScaleImage
{
    public function __construct(
        public Image $image,
    ) {}
}
