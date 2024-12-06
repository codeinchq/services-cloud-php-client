# Code Inc.'s ServicesCloud PHP client

This is a PHP client for the ServicesCloud API. It is a work in progress and is not yet ready for production use.

## Available APIs

### Office2Pdf

This API allows you to convert office documents to PDF. For more information see [this documentation](https://github.com/codeinchq/office2pdf-php-client?tab=readme-ov-file#usage).

The Office2Pdf client can be accessed using:
```php
use CodeInc\ServicesCloud\ServicesCloudClient;

// Create a new client
$servicesCloudClient = new ServicesCloudClient('my api key');

// Convert a stream using the Office2Pdf API
$response = $servicesCloudClient->office2Pdf()->convert(/* a stream */);
```

### Pdf2Img

This API allows you to convert PDF documents to images. For more information see [this documentation](https://github.com/codeinchq/pdf2img-php-client?tab=readme-ov-file#usage).

The Pdf2Img client can be accessed using:
```php
use CodeInc\ServicesCloud\ServicesCloudClient;

// Create a new client
$servicesCloudClient = new ServicesCloudClient('my api key');

// Convert a stream using the Pdf2Img API
$response = $servicesCloudClient->pdf2Img()->convert(/* a stream */);
```

### Pdf2Txt

This API allows you to convert PDF documents to text. For more information see [this documentation](https://github.com/codeinchq/pdf2txt-php-client?tab=readme-ov-file#usage).

The Pdf2Txt client can be accessed using:
```php
use CodeInc\ServicesCloud\ServicesCloudClient;

// Create a new client
$servicesCloudClient = new ServicesCloudClient('my api key');

// Convert a stream using the Pdf2Txt API
$response = $servicesCloudClient->pdf2Txt()->convert(/* a stream */);
```

## Extra API

> **⚠️ Warning**  
> Accessing the Gotenberg API requires special authorization.

The legacy Gotenberg v8 API can be accessed using the ServicesCloud client as the Gotenberg HTTP client.

Here is an example implementation:
```php
use Gotenberg\Gotenberg;
use Gotenberg\Stream;
use CodeInc\ServicesCloud\ServicesCloudClient;

$response = Gotenberg::send(
    Gotenberg::libreOffice('https://gotenberg-v8-eu-byzteify.ew.gateway.dev')->convert(/* a stream */),
    new ServicesCloudClient('my api key')
);
```