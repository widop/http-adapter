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
     * @throws \Widop\HttpAdapterBundle\Exception\HttpAdapterException If an error occured.
     *
     * @return string The response content.
     */
    protected function execute($url, $context)
    {
        if (($fp = @fopen($this->fixUrl($url), 'rb', false, $context)) === false) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), print_r(error_get_last(), true));
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
     * Creates the stream context.
     *
     * @param string       $method  The HTTP method.
     * @param array        $headers The headers.
     * @param string|array $content The content.
     *
     * @return resource A stream context resource.
     */
    protected function createStreamContext($method, array $headers, $content = '')
    {
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

        if ($method === 'POST') {
            $contextOptions['http']['content'] = $this->fixContent($content);
        }

        return stream_context_create($contextOptions);
    }
}
