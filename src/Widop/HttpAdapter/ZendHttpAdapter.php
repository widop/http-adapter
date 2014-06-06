<?php

/*
 * This file is part of the Widop package.
 *
 * (c) Widop <contact@widop.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Widop\HttpAdapter;

use Widop\HttpAdapter\HttpAdapterException;
use Zend\Http\Client;
use Zend\Http\Request;

/**
 * Zend http adapter.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ZendHttpAdapter extends AbstractHttpAdapter
{
    /** @var \Zend\Http\Client */
    private $client;

    /**
     * Creates a Zend http adapter.
     *
     * @param \Zend\Http\Client $client       The Zend client.
     * @param integer           $maxRedirects The max redirects.
     */
    public function __construct(Client $client = null, $maxRedirects = 5)
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
        return $this->sendRequest($url, Request::METHOD_GET, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function postContent($url, array $headers = array(), array $content = array(), array $files = array())
    {
        return $this->sendRequest($url, Request::METHOD_POST, $headers, $content, $files);
    }

    /**
     * {@inheritdoc}
     */
    public function head($url, array $headers = array())
    {
        return $this->sendRequest($url, Request::METHOD_HEAD, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $headers = array(), array $content = array(), array $files = array())
    {
        return $this->sendRequest($url, Request::METHOD_PUT, $headers, $content, $files);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zend';
    }

    /**
     * Sends a request.
     *
     * @param string $url     The url.
     * @param string $method  The http method.
     * @param array  $headers The headers.
     * @param array  $content The content.
     * @param array  $files   The files.
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return \Widop\HttpAdapter\HttpResponse The response.
     */
    private function sendRequest(
        $url,
        $method,
        array $headers = array(),
        array $content = array(),
        array $files = array()
    ) {
        $this->client
            ->resetParameters()
            ->setOptions(array('maxredirects' => $this->getMaxRedirects()))
            ->setMethod($method)
            ->setUri($url)
            ->setHeaders($headers)
            ->setParameterPost($content);

        foreach ($files as $key => $file) {
            $this->client->setFileUpload($file, $key);
        }

        try {
            $response = $this->client->send();
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }

        return $this->createResponse(
            $response->getStatusCode(),
            $url,
            $response->getHeaders()->toArray(),
            $method === Request::METHOD_HEAD ? '' : $response->getBody()
        );
    }
}
