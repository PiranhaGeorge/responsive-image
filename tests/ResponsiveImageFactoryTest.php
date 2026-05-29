<?php

namespace Tempest\ResponsiveImage\Tests;

use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tempest\ResponsiveImage\Exceptions\ImageSourceWasNotFound;
use Tempest\ResponsiveImage\ResponsiveImageConfig;
use Tempest\ResponsiveImage\ResponsiveImageFactory;
use Tempest\ResponsiveImage\Size;

final class ResponsiveImageFactoryTest extends TestCase
{
    #[Before, After]
    public function cleanPublicDir(): void
    {
        $files = glob(__DIR__ . '/Fixtures/public/*.jpg');

        if (! $files) {
            return;
        }

        foreach ($files as $file) {
            unlink($file);
        }
    }

    #[Test]
    public function test_create_image_sync(): void
    {
        $config = new ResponsiveImageConfig(
            srcPath: __DIR__ . '/Fixtures/src/',
            publicPath: __DIR__ . '/Fixtures/public/',
            async: false,
        );

        $factory = new ResponsiveImageFactory($config);

        $image = $factory->create('/parrot.jpg');

        $this->assertSame('/parrot.jpg', $image->src);
        $this->assertCount(4, $image->srcset);

        foreach ($image->srcset as $srcset) {
            $this->assertFileExists($config->makePublicPath($srcset->src));
        }

        $this->assertSame(
            <<<'HTML'
            <img src="/parrot.jpg" srcset="/parrot-1920-1280.jpg 1920w, /parrot-1606-1070.jpg 1606w, /parrot-1214-809.jpg 1214w, /parrot-607-404.jpg 607w">
            HTML,
            $image->html,
        );
    }

    #[Test]
    public function test_create_with_alt_and_sizes(): void
    {
        $config = new ResponsiveImageConfig(
            srcPath: __DIR__ . '/Fixtures/src/',
            publicPath: __DIR__ . '/Fixtures/public/',
            async: false,
        );

        $factory = new ResponsiveImageFactory($config);

        $image = $factory->create(
            src: '/parrot.jpg',
            alt: 'A parrot',
            sizes: [new Size(maxWidth: 1000, width: 300)],
            lazy: true,
        );

        $this->assertSame(
            <<<'HTML'
            <img src="/parrot.jpg" alt="A parrot" srcset="/parrot-1920-1280.jpg 1920w, /parrot-1606-1070.jpg 1606w, /parrot-1214-809.jpg 1214w, /parrot-607-404.jpg 607w" sizes="(max-width: 1000px) 300px" loading="lazy">
            HTML,
            $image->html,
        );
    }

    #[Test]
    public function test_source_not_found(): void
    {
        $config = new ResponsiveImageConfig(
            srcPath: __DIR__ . '/Fixtures/src/',
            publicPath: __DIR__ . '/Fixtures/public/',
            async: false,
        );

        $factory = new ResponsiveImageFactory($config);

        try {
            $factory->create('/not-found.jpg');
        } catch (ImageSourceWasNotFound $e) {
            $this->assertSame('Source for `/not-found.jpg` not found at ' . __DIR__ . '/Fixtures/src/not-found.jpg.', $e->getMessage());
        }
    }
}
