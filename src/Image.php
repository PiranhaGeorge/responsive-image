<?php

namespace Tempest\ResponsiveImage;

final class Image
{
    public function __construct(
        public string $src,
        public string $srcPath,
        public string $publicPath,
        public ?string $alt = null,
        public ?int $width = null,
        public ?int $height = null,
        /** @var SrcSet[] */
        public array $srcset = [],
        /** @var Size[] */
        public array $sizes = [],
        public bool $lazy = false,
    ) {
        $this->src = '/' . ltrim($this->src, '/');
    }

    public bool $isScalable {
        get {
            $extension = pathinfo($this->src, PATHINFO_EXTENSION);

            return in_array($extension, ['jpg', 'jpeg', 'png'], strict: true);
        }
    }

    public string $html {
        get {
            $html = '<img src="' . $this->src . '"';

            if ($this->alt) {
                $html .= ' alt="' . $this->alt . '"';
            }

            if ($this->width) {
                $html .= ' width="' . $this->width . '"';
            }

            if ($this->height) {
                $html .= ' height="' . $this->height . '"';
            }

            if ($this->srcset !== []) {
                $srcset = array_map(fn (SrcSet $srcset) => (string) $srcset, $this->srcset);

                $html .= ' srcset="' . implode(', ', $srcset) . '"';
            }

            if ($this->sizes !== []) {
                $sizes = array_map(fn (Size $sizes) => (string) $sizes, $this->sizes);

                $html .= ' sizes="' . implode(', ', $sizes) . '"';
            }

            if ($this->lazy) {
                $html .= ' loading="lazy"';
            }

            $html .= '>';

            return $html;
        }
    }
}
