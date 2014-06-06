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

/**
 * Stream Http adapter.
 *
 * @author Geoffrey Brier <geoffrey.brier@gmail.com>
 * @author GeLo <geloen.eric@gmail.com>
 */
class StreamHttpAdapter extends AbstractHttpAdapter
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
    public function postContent($url, array $headers = array(), array $content = array(), array $files = array())
    {
        return $this->execute($url, $this->createStreamContext('POST', $headers, $content, $files));
    }

    /**
     * {@inheritdoc}
     */
    public function head($url, array $headers = array())
    {
        return $this->execute($url, $this->createStreamContext('HEAD', $headers));
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $headers = array(), array $content = array(), array $files = array())
    {
        return $this->execute($url, $this->createStreamContext('PUT', $headers, $content, $files));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'stream';
    }

    /**
     * Calls an URL given a context.
     *
     * @param string   $url     An url.
     * @param resource $context A resource (created from stream_context_create).
     *
     * @throws \Widop\HttpAdapterBundle\Exception\HttpAdapterException If an error occured.
     *
     * @return @return \Widop\HttpAdapter\HttpResponse The response.
     */
    private function execute($url, $context)
    {
        if (($stream = @fopen($this->fixUrl($url), 'rb', false, $context)) === false) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), print_r(error_get_last(), true));
        }

        $metadata = stream_get_meta_data($stream);

        if (preg_match_all('#Location:([^,]+)#', implode(',', $metadata['wrapper_data']), $matches)) {
            $effectiveUrl = trim($matches[1][count($matches[1]) - 1]);
        } else {
            $effectiveUrl = $url;
        }

        $content = stream_get_contents($stream);
        fclose($stream);

        return $this->createResponse(
            isset($metadata['wrapper_data'][0]) ? $this->parseStatusCode($metadata['wrapper_data'][0]) : null,
            $url,
            $metadata['wrapper_data'],
            $content,
            $effectiveUrl
        );
    }

    /**
     * Creates the stream context.
     *
     * @param string $method  The HTTP method.
     * @param array  $headers The headers.
     * @param array  $content The content.
     * @param array  $files   The files.
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If there are files (not supported).
     *
     * @return resource A stream context resource.
     */
    private function createStreamContext($method, array $headers, array $content = array(), array $files = array())
    {
        if (!empty($files)) {
            throw new HttpAdapterException(sprintf('The "%s" does not support files.', __CLASS__));
        }

        $contextOptions = array('http' => array('method' => $method));

        if (!empty($headers)) {
            $contextOptions['http']['header'] = '';

            foreach ($this->fixHeaders($headers) as $header) {
                $contextOptions['http']['header'] .= sprintf("%s\r\n", $header);
            }
        }

        if ($this->getMaxRedirects() > 0) {
            $contextOptions['http']['follow_location'] = 1;
            $contextOptions['http']['max_redirects'] = $this->getMaxRedirects();
        }

        if ($method === 'POST' || $method === 'PUT') {
            $contextOptions['http']['content'] = $this->fixContent($content);
        }

        return stream_context_create($contextOptions);
    }

    /**
     * Parses the status code from the status line.
     *
     * @param string $status The status line.
     *
     * @return null|integer The status code.
     */
    private function parseStatusCode($status)
    {
        $parts = explode(' ', $status, 2);

        if (isset($parts[1])) {
            return (integer) $parts[1];
        }
    }
}
