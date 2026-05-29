<?php

namespace Tempest\ResponsiveImage\Exceptions;

use Exception;
use Tempest\ResponsiveImage\Image;
use Tempest\ResponsiveImage\ResponsiveImageException;

final class ImageSourceWasNotFound extends Exception implements ResponsiveImageException
{
    public function __construct(Image $image)
    {
        parent::__construct("Source for `{$image->src}` not found at {$image->srcPath}.");
    }
}