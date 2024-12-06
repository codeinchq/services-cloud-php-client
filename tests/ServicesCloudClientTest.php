<?php
/*
 * Copyright 2024 Code Inc. <https://www.codeinc.co>
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace CodeInc\ServicesCloud\Tests;

use CodeInc\ServicesCloud\ServicesCloudClient;
use PHPUnit\Framework\TestCase;

/**
 * Class Pdf2ImgClientTest
 *
 * @see     ServicesCloudClient
 * @package CodeInc\ServicesCloud\Tests
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Joan Fabrégat <joan@codeinc.co>
 */
final class ServicesCloudClientTest extends TestCase
{
    /**
     * Test the health of the services.
     *
     * @return void
     */
    public function testHealth(): void
    {
        // testing a healthy service
        $client = new ServicesCloudClient();

        // Check the health of each service
        $this->assertNotFalse(
            $client->office2Pdf()->checkServiceHealth(),
            "The Office2Pdf service is not healthy."
        );
        $this->assertNotFalse(
            $client->pdf2img()->checkServiceHealth(),
            "The Pdf2Img service is not healthy."
        );
        $this->assertNotFalse(
            $client->pdf2txt()->checkServiceHealth(),
            "The Pdf2Txt service is not healthy."
        );
        $this->assertNotFalse(
            $client->watermarker()->checkServiceHealth(),
            "The Watermarker service is not healthy."
        );

        // Check the health of all the services
        $servicesHealth = $client->checkServicesHealth();
        $this->assertHealthIs($servicesHealth, true);
    }

    /**
     * Test the health of the services with an invalid API key.
     *
     * @return void
     */
    public function testHealthInvalidKey(): void
    {
        $failingClient = new ServicesCloudClient('invalid-api-key');
        $servicesHealth = $failingClient->checkServicesHealth();
        $this->assertHealthIs($servicesHealth, false);
    }

    /**
     * Test the health of the services with an invalid URL.
     *
     * @return void
     */
    public function testHealthInvalidUrl(): void
    {
        $failingClient = new ServicesCloudClient(null, 'https://example.com');
        $servicesHealth = $failingClient->checkServicesHealth();
        $this->assertHealthIs($servicesHealth, false);
    }

    public function assertHealthIs(array $servicesHealth, bool $is): void
    {
        $this->assertArrayHasKey('office2pdf', $servicesHealth);
        $this->assertEquals($servicesHealth['office2pdf'], $is);
        $this->assertArrayHasKey('pdf2img', $servicesHealth);
        $this->assertEquals($servicesHealth['pdf2img'], $is);
        $this->assertArrayHasKey('pdf2txt', $servicesHealth);
        $this->assertEquals($servicesHealth['pdf2txt'], $is);
        $this->assertArrayHasKey('watermarker', $servicesHealth);
        $this->assertEquals($servicesHealth['watermarker'], $is);
    }
}