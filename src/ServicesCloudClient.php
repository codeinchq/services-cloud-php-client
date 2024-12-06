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
use CodeInc\WatermarkerClient\WatermarkerClient;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\VarDumper\Caster\TraceStub;
use Symfony\Component\VarDumper\Command\Descriptor\DumpDescriptorInterface;

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
    public const string EU_API_URL = 'https://services-cloud-v3-eu-byzteify.ew.gateway.dev';
    public const string US_API_URL = '';

    private readonly string $apiKey;
    private readonly string $apiUrl;
    private readonly ClientInterface $client;
    private ?Office2PdfClient $office2PdfClient = null;
    private ?Pdf2ImgClient $pdf2ImgClient = null;
    private ?Pdf2TxtClient $pdf2TxtClient = null;
    private ?WatermarkerClient $watermarkerClient = null;

    /**
     * ServicesCloudClient constructor.
     *
     * @param string|null $apiKey          The API key or null to use the SERVICES_CLOUD_API_KEY environment variable.
     * @param string|null $apiUrl          The API URL or null to use the SERVICES_CLOUD_API_URL environment variable.
     * @param ClientInterface|null $client The HTTP client.
     * @throws Exception If the API URL is not valid.
     */
    public function __construct(
        ?string $apiKey = null,
        ?string $apiUrl = null,
        ?ClientInterface $client = null,
    ) {
        if ($apiKey === null) {
            if (empty($_ENV['SERVICES_CLOUD_API_KEY'])) {
                var_dump($_ENV);
                var_dump(getenv('SERVICES_CLOUD_API_KEY'));
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

        $this->apiUrl = $apiUrl ?? $_ENV['SERVICES_CLOUD_API_URL'] ?? self::DEFAULT_API_URL;
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
        $this->office2PdfClient ??= new Office2PdfClient($this->getServiceUrl('office2pdf', 'v1'), $this);
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
        $this->pdf2ImgClient ??= new Pdf2ImgClient($this->getServiceUrl('pdf2img', 'v1'), $this);
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
        $this->pdf2TxtClient ??= new Pdf2TxtClient($this->getServiceUrl('pdf2txt', 'v1'), $this);
        return $this->pdf2TxtClient;
    }

    /**
     * Returns the Watermarker client.
     *
     * @return WatermarkerClient
     * @see https://github.com/codeinchq/watermarker-php-client
     */
    public function watermarker(): WatermarkerClient
    {
        $this->watermarkerClient ??= new WatermarkerClient($this->getServiceUrl('watermarker', 'v1'), $this);
        return $this->watermarkerClient;
    }

    /**
     * Returns the API URL for a service.
     *
     * @param string $service
     * @param string $version
     * @return string
     */
    private function getServiceUrl(string $service, string $version): string
    {
        $url = $this->apiUrl;
        if (!str_ends_with($url, '/')) {
            $url .= '/';
        }
        return "$url$service/$version/";
    }

    /**
     * Checks the health of the services.
     *
     * @return array
     */
    public function checkServicesHealth(): array
    {
        return [
            'office2pdf' => $this->office2Pdf()->checkServiceHealth(),
            'pdf2img' => $this->pdf2img()->checkServiceHealth(),
            'pdf2txt' => $this->pdf2txt()->checkServiceHealth(),
            'watermarker' => $this->watermarker()->checkServiceHealth(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest(
            $request->withHeader('X-Api-Key', $this->apiKey)
        );
    }
}