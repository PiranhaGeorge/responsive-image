<?php

namespace Tempest\ResponsiveImage;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Tempest\Container\Container;
use Tempest\Container\Initializer;
use Tempest\Container\Singleton;

final readonly class ResponsiveImageFactoryInitializer implements Initializer
{
    #[Singleton]
    public function initialize(Container $container): ResponsiveImageFactory
    {
        return new ResponsiveImageFactory(
            $container->get(ResponsiveImageConfig::class),
            new ImageManager(driver: Driver::class),
        );
    }
}
