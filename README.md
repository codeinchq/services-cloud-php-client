# Code Inc.'s ServicesCloud PHP client

[![Code Inc.](https://img.shields.io/badge/Code%20Inc.-Services%20Cloud-blue)](https://www.codeinc.co)
![Tests](https://github.com/codeinchq/services-cloud-php-client/actions/workflows/phpunit.yml/badge.svg)

This is a PHP client for the ServicesCloud API that allows you to access the services provided by Code Inc. through a simple and easy-to-use interface. 

> [!CAUTION]
> It is a work in progress and is not yet ready for production use.

## Installation

The library is available on [Packagist](https://packagist.org/packages/codeinc/services-cloud-client). The recommended way to install it is via Composer:

```bash
composer require codeinc/services-cloud-client
```

## Available APIs

### Office2Pdf API

This API allows you to convert office documents to PDF. For more information see [this documentation](https://github.com/codeinchq/office2pdf-php-client?tab=readme-ov-file#usage).

The Office2Pdf client can be accessed using:
```php
use CodeInc\ServicesCloud\ServicesCloudClient;

// Create a new client
$servicesCloudClient = new ServicesCloudClient('my api key');

// Convert a stream using the Office2Pdf API
$response = $servicesCloudClient->office2Pdf()->convert(/* an Office stream */);
```

### Pdf2Img API

This API allows you to convert PDF documents to images. For more information see [this documentation](https://github.com/codeinchq/pdf2img-php-client?tab=readme-ov-file#usage).

The Pdf2Img client can be accessed using:
```php
use CodeInc\ServicesCloud\ServicesCloudClient;

// Create a new client
$servicesCloudClient = new ServicesCloudClient('my api key');

// Convert a stream using the Pdf2Img API
$response = $servicesCloudClient->pdf2Img()->convert(/* a PDF stream */);
```

### Pdf2Txt API

This API allows you to convert PDF documents to text. For more information see [this documentation](https://github.com/codeinchq/pdf2txt-php-client?tab=readme-ov-file#usage).

The Pdf2Txt client can be accessed using:
```php
use CodeInc\ServicesCloud\ServicesCloudClient;

// Create a new client
$servicesCloudClient = new ServicesCloudClient('my api key');

// Extract text using the Pdf2Txt API
$response = $servicesCloudClient->pdf2Txt()->extract(/* a PDF stream */);
```

### Watermarker API

This API allows you to add a watermark to a PDF document. For more information see [this documentation](https://github.com/codeinchq/watermarker-php-client?tab=readme-ov-file#usage).

The Watermarker client can be accessed using:
```php
use CodeInc\ServicesCloud\ServicesCloudClient;

// Create a new client
$servicesCloudClient = new ServicesCloudClient('my api key');

// Apply a watermark using the Watermarker API
$response = $servicesCloudClient->watermarker()->apply(
    /* an image stream*/, 
    /* a PDF stream */
);
```

### Gotenberg API (legacy)

> [!WARNING]  
> By default API keys are not authorized to access the Gotenberg API. If you need access to the Gotenberg API, please contact Code Inc. to request authorization.

The legacy Gotenberg v8 API can be accessed using the ServicesCloud client as the Gotenberg HTTP client.

Here is an example implementation:
```php
use Gotenberg\Gotenberg;
use Gotenberg\Stream;
use CodeInc\ServicesCloud\ServicesCloudClient;

$response = Gotenberg::send(
    Gotenberg::libreOffice('https://gotenberg-v8-eu-byzteify.ew.gateway.dev')->convert(/* an Office stream */),
    new ServicesCloudClient('my api key')
);
```

## License

The library is published under the MIT license (see [`LICENSE`](LICENSE) file).