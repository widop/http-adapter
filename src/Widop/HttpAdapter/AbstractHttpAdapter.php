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
 * Abstract http adapter.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractHttpAdapter implements HttpAdapterInterface
{
    /** @var integer */
    private $maxRedirects;

    /**
     * Creates an Http adapter.
     *
     * @param integer $maxRedirects The maximum redirects.
     */
    public function __construct($maxRedirects = 5)
    {
        $this->setMaxRedirects($maxRedirects);
    }

    /**
     * Gets the maximum redirects.
     *
     * @return integer The maximum redirects.
     */
    public function getMaxRedirects()
    {
        return $this->maxRedirects;
    }

    /**
     * Sets the maximum redirects.
     *
     * @param integer $maxRedirects The maximum redirects.
     */
    public function setMaxRedirects($maxRedirects)
    {
        $this->maxRedirects = $maxRedirects;
    }

    /**
     * Fixes the URL to match the http format.
     *
     * @param string $url The url.
     *
     * @return string The fixed url.
     */
    protected function fixUrl($url)
    {
        if ((strpos($url, 'http://') !== 0) && (strpos($url, 'https://') !== 0)) {
            return sprintf('http://%s', $url);
        }

        return $url;
    }

    /**
     * Fixes the headers to match the http format.
     *
     * @param array $headers The headers.
     *
     * @return array The fixes headers.
     */
    protected function fixHeaders(array $headers)
    {
        $fixedHeaders = array();

        foreach ($headers as $key => $value) {
            if (is_int($key)) {
                $fixedHeaders[] = $value;
            } else {
                $fixedHeaders[] = sprintf('%s:%s', $key, $value);
            }
        }

        return $fixedHeaders;
    }

    /**
     * Fixes the content to match the http format.
     *
     * @param array|string $content The content.
     *
     * @return string The content.
     */
    protected function fixContent($content)
    {
        return is_array($content) ? http_build_query($content) : $content;
    }

    /**
     * Creates an Http response.
     *
     * @param integer|null $statusCode   The response status code.
     * @param string       $url          The response URL.
     * @param string|array $headers      The response headers.
     * @param string       $body         The response body.
     * @param string|null  $effectiveUrl The response effective URL.
     *
     * @return \Widop\HttpAdapter\HttpResponse The response.
     */
    protected function createResponse($statusCode, $url, $headers, $body, $effectiveUrl = null)
    {
        return new HttpResponse($statusCode, $url, $this->createHeaders($headers), $body, $effectiveUrl);
    }

    /**
     * Creates the headers.
     *
     * @param string|array $headers The headers.
     *
     * @return array The created headers.
     */
    private function createHeaders($headers)
    {
        if (is_string($headers)) {
            return $this->createHeaders(explode("\r\n", $headers));
        }

        $fixedHeaders = array();

        foreach ($headers as $key => $header) {
            if (is_int($key)) {
                if (($pos = strpos($header, ':')) === false) {
                    continue;
                }

                $fixedHeaders[substr($header, 0, $pos)] = substr($header, $pos + 1);
            } else {
                $fixedHeaders[$key] = $this->createHeader($header);
            }
        }

        return $fixedHeaders;
    }

    /**
     * Creates an header.
     *
     * @param string|array $header The header.
     *
     * @return string The created header.
     */
    private function createHeader($header)
    {
        if (is_array($header)) {
            return implode(';', $header);
        }

        return $header;
    }
}
