<?php
/*
 * Copyright 2024 Code Inc. <https://www.codeinc.co>
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 *
 */

namespace CodeInc\ServicesCloud;

use CodeInc\Office2PdfClient\Office2PdfClient;
use CodeInc\Pdf2ImgClient\Pdf2ImgClient;
use CodeInc\Pdf2TxtClient\Pdf2TxtClient;
use CodeInc\WatermarkerClient\WatermarkerClient;

/**
 * ServicesCloud client.
 *
 * @author Joan Fabrégat <joan@codeinc.co>
 */
class ServicesCloud
{
    private readonly Client $client;
    private ?Office2PdfClient $office2PdfClient = null;
    private ?Pdf2ImgClient $pdf2ImgClient = null;
    private ?Pdf2TxtClient $pdf2TxtClient = null;
    private ?WatermarkerClient $watermarkerClient = null;

    /**
     * ServicesCloud constructor.
     *
     * @param string $apiKLey The API key.
     */
    public function __construct(
        string $apiKLey
    ) {
        $this->client = new Client($apiKLey);
    }

    /**
     * @return Office2PdfClient
     */
    public function getOffice2PdfClient(): Office2PdfClient
    {
        return $this->office2PdfClient ??= new Office2PdfClient(
            'https://office2pdf-v1-eu-60wornff.ew.gateway.dev',
            $this->client
        );
    }

    public function getPdf2ImgClient(): Pdf2ImgClient
    {
        return $this->pdf2ImgClient ??= new Pdf2ImgClient(
            'https://pdf2img-v1-eu-60wornff.ew.gateway.dev',
            $this->client
        );
    }

    public function getPdf2TxtClient(): Pdf2TxtClient
    {
        return $this->pdf2TxtClient ??= new Pdf2TxtClient(
            'https://pdf2txt-v1-eu-60wornff.ew.gateway.dev',
            $this->client
        );
    }

    public function getWatermarkerClient(): WatermarkerClient
    {
        return $this->watermarkerClient ??= new WatermarkerClient(
            'https://watermarker-v1-eu-60wornff.ew.gateway.dev',
            $this->client
        );
    }
}