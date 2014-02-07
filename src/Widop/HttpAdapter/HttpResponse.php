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

/**
 * Http response.
 *
 * @author GeLo <geloen.eric@gmail.com>
 * @author Dudu <clement.duez@widop.com>
 */
class HttpResponse
{
    /** @var string */
    private $url;

    /** @var array */
    private $headers;

    /** @var string */
    private $body;

    /** @var string */
    private $effectiveUrl;

    /**
     * Creates an http response.
     *
     * @param string      $url          The response url.
     * @param array       $headers      The response headers.
     * @param string      $body         The response body.
     * @param null|string $effectiveUrl The response effective url.
     */
    public function __construct($url, array $headers, $body, $effectiveUrl = null)
    {
        $this->url = $url;
        $this->body = $body;
        $this->effectiveUrl = $effectiveUrl;

        $this->headers = array();
        foreach ($headers as $key => $value) {
            $this->headers[strtolower($key)] = $value;
        }
    }

    /**
     * Gets the url.
     *
     * @return string The url.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Gets the headers.
     *
     * @return array The headers.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Gets a header.
     *
     * @param string $name The header name.
     *
     * @return mixed The header.
     */
    public function getHeader($name)
    {
        $name = strtolower($name);

        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    /**
     * Gets the body.
     *
     * @return string The body.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Gets the effective url.
     *
     * @return string The effective url.
     */
    public function getEffectiveUrl()
    {
        return $this->effectiveUrl;
    }
}
