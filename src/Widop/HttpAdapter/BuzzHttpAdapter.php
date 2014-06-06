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

use Buzz\Browser;
use Buzz\Message\RequestInterface;

/**
 * Buzz Http adapter.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class BuzzHttpAdapter extends AbstractHttpAdapter
{
    /** @var \Buzz\Browser */
    private $browser;

    /**
     * Constructor.
     *
     * @param \Buzz\Browser $browser      The buzz browser.
     * @param integer       $maxRedirects The maximum redirects.
     */
    public function __construct(Browser $browser = null, $maxRedirects = 5)
    {
        parent::__construct($maxRedirects);

        if ($browser === null) {
            $browser = new Browser();
        }

        $this->browser = $browser;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent($url, array $headers = array())
    {
        return $this->sendRequest($url, RequestInterface::METHOD_GET, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function postContent($url, array $headers = array(), array $content = array(), array $files = array())
    {
        if (!empty($files)) {
            $content = array_merge($content, array_map(function($file) { return '@'.$file; }, $files));
        }

        return $this->sendRequest($url, RequestInterface::METHOD_POST, $headers, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function head($url, array $headers = array())
    {
        return $this->sendRequest($url, RequestInterface::METHOD_HEAD, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $headers = array(), array $content = array(), array $files = array())
    {
        if (!empty($files)) {
            $content = array_merge($content, array_map(function($file) { return '@'.$file; }, $files));
        }

        return $this->sendRequest($url, RequestInterface::METHOD_PUT, $headers, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'buzz';
    }

    /**
     * Sends a request.
     *
     * @param string $url     The url.
     * @param string $method  The http method.
     * @param array  $headers The header.
     * @param array  $content The content.
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return \Widop\HttpAdapter\HttpResponse The response.
     */
    private function sendRequest($url, $method, array $headers = array(), array $content = array())
    {
        $this->browser->getClient()->setMaxRedirects($this->getMaxRedirects());

        try {
            $response = $this->browser->call($url, $method, $headers, $content);
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }

        return $this->createResponse(
            $response->getStatusCode(),
            $url,
            $response->getHeaders(),
            $response->getContent()
        );
    }
}
