<?php
/*
 * Copyright (c) 2024 Code Inc. - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Visit <https://www.codeinc.co> for more information
 */

namespace CodeInc\ServicesCloud;

use CodeInc\Office2PdfClient\Office2PdfClient;
use CodeInc\Pdf2ImgClient\Pdf2ImgClient;
use CodeInc\Pdf2TxtClient\Pdf2TxtClient;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * ServicesCloud API client. This library allows you to interact with the Service Cloud API. It provides a simple way to
 * convert office documents to PDF, PDF to images and PDF to text.
 *
 * @see     https://www.codeinc.co/services-cloud
 * @license https://opensource.org/licenses/MIT
 * @author  Joan Fabrégat <joan@codeinc.co>
 */
class ServicesCloudClient implements ClientInterface
{
    public const string DEFAULT_API_URL = self::EU_API_URL;
    public const string EU_API_URL = 'https://services-cloud-v1-1-eu-byzteify.ew.gateway.dev/';
    public const string US_API_URL = '';

    private readonly string $apiKey;
    private readonly string $apiUrl;
    private readonly ClientInterface $client;
    private ?Office2PdfClient $office2PdfClient = null;
    private ?Pdf2ImgClient $pdf2ImgClient = null;
    private ?Pdf2TxtClient $pdf2TxtClient = null;

    /**
     * ServicesCloudClient constructor.
     *
     * @param string|null $apiKey          The API key or null to use the SERVICES_CLOUD_API_KEY environment variable.
     * @param string|null $apiUrl          The API URL or null to use the SERVICES_CLOUD_API_URL environment variable.
     * @param ClientInterface|null $client The HTTP client.
     * @throws Exception If the API URL is not valid.
     */
    public function __construct(
        string $apiKey = null,
        string $apiUrl = null,
        ClientInterface $client = null,
    ) {
        if ($apiKey === null) {
            if (empty($_ENV['SERVICES_CLOUD_API_KEY'])) {
                throw new Exception(
                    'The API key must be provided as an argument or configured in the SERVICES_CLOUD_API_KEY environment variable.'
                );
            }
            $this->apiKey = $_ENV['SERVICES_CLOUD_API_KEY'];
        } else {
            if (empty($apiKey)) {
                throw new Exception("The API key can not be empty.");
            }
            $this->apiKey = $apiKey;
        }

        $this->apiUrl = $apiUrl ?? $_ENV['SERVICES_CLOUD_API_URL'] ?: self::DEFAULT_API_URL;
        if (!filter_var($this->apiUrl, FILTER_VALIDATE_URL)) {
            throw new Exception('The API URL is not valid.');
        }

        $this->client = $client ?? Psr18ClientDiscovery::find();
    }

    /**
     * Returns the Office2Pdf client.
     *
     * @see https://github.com/codeinchq/office2pdf-php-client
     * @return Office2PdfClient
     */
    public function office2Pdf(): Office2PdfClient
    {
        $this->office2PdfClient ??= new Office2PdfClient("{$this->apiUrl}office2pdf/", $this);
        return $this->office2PdfClient;
    }

    /**
     * Returns the Pdf2Img client.
     *
     * @see https://github.com/codeinchq/pdf2img-php-client
     * @return Pdf2ImgClient
     */
    public function pdf2img(): Pdf2ImgClient
    {
        $this->pdf2ImgClient ??= new Pdf2ImgClient("{$this->apiUrl}pdf2img/", $this);
        return $this->pdf2ImgClient;
    }

    /**
     * Returns the Pdf2Txt client.
     *
     * @see https://github.com/codeinchq/pdf2txt-php-client
     * @return Pdf2TxtClient
     */
    public function pdf2txt(): Pdf2TxtClient
    {
        $this->pdf2TxtClient ??= new Pdf2TxtClient("{$this->apiUrl}pdf2txt/", $this);
        return $this->pdf2TxtClient;
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $request = $request->withHeader('X-Api-Key', $this->apiKey);

        return $this->client->sendRequest($request);
    }
}