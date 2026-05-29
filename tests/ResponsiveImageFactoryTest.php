<?php

namespace Tempest\ResponsiveImage\Tests;

use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tempest\ResponsiveImage\ResponsiveImageConfig;
use Tempest\ResponsiveImage\ResponsiveImageFactory;

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
}
