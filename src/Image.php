<?php

namespace Tempest\ResponsiveImage;

final class Image
{
    public string $src;

    public string $srcPath;

    public string $publicPath;

    public ?string $alt = null;

    /** @var SrcSet[] */
    public array $srcset = [];

    public function __construct(
        string $src,
        string $srcPath,
        string $publicPath,
        ?string $alt = null,
    ) {
        $this->src = '/' . ltrim($src, '/');
        $this->srcPath = $srcPath;
        $this->publicPath = $publicPath;
        $this->alt = $alt;
    }

    public bool $isScalable {
        get {
            $extension = pathinfo($this->src, PATHINFO_EXTENSION);

            return in_array($extension, ['jpg', 'jpeg', 'png']);
        }
    }

    public string $html {
        get {
            $html = '<img src="' . $this->src . '"';

            if ($this->alt) {
                $html .= ' alt="' . $this->alt . '"';
            }

            if ($this->srcset) {
                $srcset = array_map(fn (SrcSet $srcset) => (string) $srcset, $this->srcset);

                $html .= ' srcset="' . implode(', ', $srcset) . '"';
            }

            $html .= '>';

            return $html;
        }
    }
}
