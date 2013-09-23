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

/**
 * Stream Http adapter.
 *
 * @author Geoffrey Brier <geoffrey.brier@gmail.com>
 */
class StreamHttpAdapter implements HttpAdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getContent($url, array $headers = array())
    {
        return $this->execute($url, $this->createStreamContext('GET', $headers));
    }

    /**
     * {@inheritdoc}
     */
    public function postContent($url, array $headers = array(), $content = '')
    {
        return $this->execute($url, $this->createStreamContext('POST', $headers, $content));
    }

    /**
     * Calls an URL given a context.
     *
     * @param string   $url     An url.
     * @param resource $context A resource (created from stream_context_create).
     *
     * @throws \Widop\HttpAdapterBundle\Exception\HttpAdapterException On url opening/fetching error.
     *
     * @return string The response content.
     */
    protected function execute($url, $context)
    {
        if (($fp = @fopen($this->fixUrl($url), 'rb', false, $context)) === false) {
            throw HttpAdapterException::cannotOpenUrl($url, $this->getName(), print_r(error_get_last(), true));
        }

        $content = stream_get_contents($fp);

        fclose($fp);

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'stream';
    }

    /**
     * Fixes the URL (adds http:// if not set).
     *
     * @param string $url An URL.
     *
     * @return string A valid URL.
     */
    protected function fixUrl($url)
    {
        if (strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
            return 'http://'.$url;
        }

        return $url;
    }

    /**
     * Creates the stream context.
     *
     * @param string $method  The HTTP method (eg: GET, POST).
     * @param array  $headers An array of headers.
     * @param string $content The content.
     *
     * @return resource A stream context resource.
     */
    protected function createStreamContext($method, array $headers, $content = '')
    {
        $rationalizedHeaders = array();
        $contextOptions = array('http' => array('method' => $method));

        // Rationalizes headers as an associative array
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                if (is_int($key)) {
                    list ($key, $value) = $this->extractHeaderKeyAndValue($value);
                }

                $rationalizedHeaders[$key] = trim($value);
            }
        }

        // Sets POST data
        if ($method === 'POST') {
            if (is_array($content)) {
                $content = http_build_query($content);
            }

            $contextOptions['http']['content'] = $content;

            if (!$this->headerKeyMatches($rationalizedHeaders, 'Content-Length')) {
                $rationalizedHeaders['Content-Length'] = strlen($content);
            }
            if (!$this->headerKeyMatches($rationalizedHeaders, 'Content-type')) {
                $rationalizedHeaders['Content-type'] = 'application/x-www-form-urlencoded';
            }
        }

        // Set headers
        if (!empty($rationalizedHeaders)) {
            $contextOptions['http']['header'] = '';
            foreach ($rationalizedHeaders as $hKey => $hValue) {
                $contextOptions['http']['header'] .= "$hKey:$hValue\r\n";
            }
        }

        return stream_context_create($contextOptions);
    }

    /**
     * Extracts an header key ('Content-Length') and an header value ('42') from an
     * header line ('Content-Length: 42').
     *
     * @param string $header The header line.
     *
     * @throws \Widop\HttpAdapterBundle\Exception\HttpAdapterException On invalid header.
     *
     * @return array An array representing a header (0: Content-Length, 1: '42').
     */
    protected function extractHeaderKeyAndValue($header)
    {
        if (($pos = strpos($header, ':')) === false) {
            throw HttpAdapterException::invalidHeader($header, 'Missing ":"');
        }

        $key = substr($header, 0, $pos);
        $value = trim(substr($header, $pos + 1));

        if (empty($key) || empty($value)) {
            throw HttpAdapterException::invalidHeader($header, 'Empty key or value');
        }

        return array($key, $value);
    }

    /**
     * Checks if $headerKey is in the associative array of headers (case insensitive).
     *
     * @param array  $headers   An associative array of headers ('Content-Type' => 'html/css').
     * @param string $headerKey The header key to look for.
     *
     * @return boolean True when the key was found.
     */
    protected function headerKeyMatches(array $headers, $headerKey)
    {
        foreach (array_keys($headers) as $hKey) {
            if (strcasecmp($hKey, $headerKey) === 0) {
                return true;
            }
        }

        return false;
    }
}
