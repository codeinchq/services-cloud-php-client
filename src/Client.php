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

use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * PSR-18 client for the ServicesCloud API.
 *
 * @author Joan Fabrégat <joan@codeinc.co>
 */
readonly class Client implements ClientInterface
{
    private ClientInterface $client;

    /**
     * Client constructor.
     *
     * @param string $apiKey               The API key.
     * @param ClientInterface|null $client The HTTP client.
     */
    public function __construct(
        private string $apiKey,
        ClientInterface $client = null,
    ) {
        $this->client = $client ?? Psr18ClientDiscovery::find();
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