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

$imageFactory = new ResponsiveImageFactory();

$image = $imageFactory->create('/');
```