<?php

namespace Tempest\ResponsiveImage\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tempest\ResponsiveImage\Image;
use Tempest\ResponsiveImage\Size;
use Tempest\ResponsiveImage\SrcSet;

class ImageTest extends TestCase
{
    #[Test]
    public function test_html_without_attributes(): void
    {
        $image = new Image(
            src: '/parrot.jpg',
            srcPath: '/src/parrot.jpg',
            publicPath: '/public/parrot.jpg',
        );

        $this->assertSame(
            <<<'HTML'
            <img src="/parrot.jpg">
            HTML,
            $image->html,
        );
    }

    #[Test]
    public function test_html_with_all_attributes(): void
    {
        $image = new Image(
            src: '/parrot.jpg',
            srcPath: '/src/parrot.jpg',
            publicPath: '/public/parrot.jpg',
            alt: 'A parrot',
            srcset: [
                new SrcSet('/parrot-1920-1280.jpg', 1920, 1280),
                new SrcSet('/parrot-1606-1070.jpg', 1606, 1070),
            ],
            sizes: [
                new Size(maxWidth: 1500, width: 500),
                new Size(maxWidth: 1000, width: 300),
            ],
            width: 1920,
            height: 1280,
            lazy: true,
        );

        $this->assertSame(
            <<<'HTML'
            <img src="/parrot.jpg" alt="A parrot" width="1920" height="1280" srcset="/parrot-1920-1280.jpg 1920w, /parrot-1606-1070.jpg 1606w" sizes="(max-width: 1500px) 500px, (max-width: 1000px) 300px" loading="lazy">
            HTML,
            $image->html,
        );
    }
}
