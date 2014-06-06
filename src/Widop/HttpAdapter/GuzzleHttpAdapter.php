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
use Guzzle\Http\Message\RequestInterface;

/**
 * Guzzle Http adapter.
 *
 * @author Gunnar Lium <gunnarlium@gmail.com>
 */
class GuzzleHttpAdapter extends AbstractHttpAdapter
{
    /** @var \Guzzle\Http\ClientInterface */
    private $client;

    /**
     * Creates a guzzle adapter.
     *
     * @param \Guzzle\Http\ClientInterface $client       The guzzle client.
     * @param integer                      $maxRedirects The maximum redirects.
     */
    public function __construct(ClientInterface $client = null, $maxRedirects = 5)
    {
        parent::__construct($maxRedirects);

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
        return $this->sendRequest($this->client->get($url, $headers));
    }

    /**
     * {@inheritdoc}
     */
    public function postContent($url, array $headers = array(), array $content = array(), array $files = array())
    {
        $request = $this->client->post($url, $headers, $content);

        foreach ($files as $key => $file) {
            $request->addPostFile($key, $file);
        }

        return $this->sendRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function head($url, array $headers = array())
    {
        return $this->sendRequest($this->client->head($url, $headers));
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $headers = array(), array $content = array(), array $files = array())
    {
        $request = $this->client->put($url, $headers, $content);

        foreach ($files as $key => $file) {
            $request->addPostFile($key, $file);
        }

        return $this->sendRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'guzzle';
    }

    /**
     * Sends a request.
     *
     * @param \Guzzle\Http\Message\RequestInterface $request The request.
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return \Widop\HttpAdapter\HttpResponse The response.
     */
    private function sendRequest(RequestInterface $request)
    {
        $request->getParams()->set('redirect.max', $this->getMaxRedirects());

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($request->getUrl(), $this->getName(), $e->getMessage());
        }

        return $this->createResponse(
            $response->getStatusCode(),
            $request->getUrl(),
            $response->getHeaders()->toArray(),
            $response->getBody(true),
            $response->getEffectiveUrl()
        );
    }
}
