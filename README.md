# Responsive images with PHP

Generate responsive images with PHP and render the correct HTML.

> [!IMPORTANT]
> This package is still a work in progress! Feel free to open issues.

## Quickstart

```sh
composer require tempest/responsive-image
```

```php
use Tempest\ResponsiveImage\ResponsiveImageFactory;
use Tempest\ResponsiveImage\ResponsiveImageConfig;

$config = new ResponsiveImageConfig(
    srcPath: __DIR__ . '/path/to/image/sources',
    publicPath: __DIR__ . '/../public',
);

$imageFactory = new ResponsiveImageFactory($config);

$image = $imageFactory->create('/parrot.jpg');

echo $image->html;

// <img src="/parrot.jpg" srcset="/parrot-1920-1280.jpg 1920w, /parrot-1606-1070.jpg 1606w, /parrot-1214-809.jpg 1214w, /parrot-607-404.jpg 607w">
```