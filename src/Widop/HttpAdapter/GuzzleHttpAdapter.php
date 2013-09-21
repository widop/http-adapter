<?php

/*
 * This file is part of the Wid'op package.
 *
 * (c) Wid'op <contact@widop.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Widop\HttpAdapter;

use Guzzle\Http\Client;
use Guzzle\Http\ClientInterface;

/**
 * Guzzle Http adapter.
 *
 * @author Gunnar Lium <gunnarlium@gmail.com>
 */
class GuzzleHttpAdapter implements HttpAdapterInterface
{
    /** @var \Guzzle\Http\ClientInterface */
    private $client;

    /**
     * Creates a guzzle adapter.
     *
     * @param \Guzzle\Http\ClientInterface $client The guzzle client.
     */
    public function __construct(ClientInterface $client = null)
    {
        if ($client === null) {
            $client = new Client();
        }

        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent($url, array $headers = array())
    {
        try {
            $response = $this->client->get($url, $headers)->send();
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }

        return $response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function postContent($url, array $headers = array(), $content = '')
    {
        try {
            $response = $this->client->post($url, $headers, $content)->send();
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }

        return $response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'guzzle';
    }
}
