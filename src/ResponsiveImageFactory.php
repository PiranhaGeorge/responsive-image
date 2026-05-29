<?php

namespace Tempest\ResponsiveImage;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Tempest\CommandBus\CommandHandler;

use function Tempest\CommandBus\command;

final readonly class ResponsiveImageFactory
{
    public function __construct(
        private ResponsiveImageConfig $config,
        private ImageManager $imageManager = new ImageManager(new Driver()),
    ) {}

    public function create(string $src): Image
    {
        $image = new Image(
            src: $src,
            srcPath: $this->config->makeSrcPath($src),
            publicPath: $this->config->makePublicPath($src),
        );

        if (! is_file($image->srcPath)) {
            // TODO: throw exception
        }

        if ($image->isScalable) {
            foreach ($this->getVariations($image) as $srcset) {
                $image->srcset[] = $srcset;
            }
        }

        if ($this->config->cache && is_file($image->publicPath)) {
            return $image;
        }

        if ($image->isScalable) {
            $this->scale($image);
        }

        $dir = pathinfo($image->publicPath, PATHINFO_DIRNAME);

        if (! is_dir($dir)) {
            mkdir($dir, recursive: true);
        }

        copy($image->srcPath, $image->publicPath);

        return $image;
    }

    #[CommandHandler]
    public function onScaleImage(ScaleImage $command): void
    {
        $image = $command->image;

        $scalableImage = $this->imageManager->decodePath($image->srcPath);

        foreach ($image->srcset as $srcset) {
            $scalableImage = $scalableImage
                ->resize($srcset->width, $srcset->height)
                ->save($this->config->makePublicPath($srcset->src));
        }
    }

    /** @return SrcSet[] */
    private function getVariations(Image $image): array
    {
        $fileSize = filesize($image->srcPath);

        if (! $fileSize) {
            return [];
        }

        $scalableImage = $this->imageManager->decodePath($image->srcPath);

        $width = $scalableImage->width();

        $ratio = $scalableImage->height() / $width;
        $area = $width * $width * $ratio;
        $pixelPrice = $fileSize / $area;

        $stepAmount = $fileSize * 0.3;

        $variations = [];

        $pathInfo = pathinfo($image->src);

        if (! isset($pathInfo['extension'], $pathInfo['filename'], $pathInfo['dirname'])) {
            return [];
        }

        $baseSrc = rtrim($pathInfo['dirname'], '/') . '/' . ltrim($pathInfo['filename'], '/');
        $extension = $pathInfo['extension'];

        do {
            $newWidth = (int) floor(sqrt(($fileSize / $pixelPrice) / $ratio));
            $newHeight = (int) floor($newWidth * $ratio);

            $variations[] = new SrcSet(
                src: "{$baseSrc}-{$newWidth}-{$newHeight}.{$extension}",
                width: $newWidth,
                height: $newHeight,
            );

            $fileSize -= $stepAmount;
        } while ($fileSize > 0);

        return $variations;
    }

    private function scale(Image $image): void
    {
        $command = new ScaleImage($image);

        if ($this->config->async) {
            command($command);
        } else {
            $this->onScaleImage($command);
        }
    }
}
