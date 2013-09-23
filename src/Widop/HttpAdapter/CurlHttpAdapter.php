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
 * Curl Http adapter.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class CurlHttpAdapter implements HttpAdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getContent($url, array $headers = array())
    {
        return $this->execute($url, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function postContent($url, array $headers = array(), $content = '')
    {
        return $this->execute($url, $headers, $content, function ($curl) use ($content) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($content) ? http_build_query($content) : $content);
        });
    }

    /**
     * Fetches the content from an URL.
     *
     * @param string   $url      A valid URL.
     * @param array    $headers  Http headers.
     * @param string   $content  Http content (in case of POST method).
     * @param callable $callback A callable function.
     *
     * @return string The response content.
     */
    protected function execute($url, array $headers = array(), $content = '', $callback = null)
    {
        $curl = $this->initCurl();

        $this->setHeaders($curl, $headers);

        curl_setopt($curl, CURLOPT_URL, $url);

        if ($callback !== null) {
            if (!is_callable($callback)) {
                throw HttpAdapterException::invalidCallback($callback);
            }

            call_user_func($callback, $curl);
        }

        $content = curl_exec($curl);

        if ($content === false) {
            $error = curl_error($curl);

            curl_close($curl);

            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $error);
        }

        curl_close($curl);

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'curl';
    }

    /**
     * Initializes cUrl.
     *
     * @return resource The curl resource.
     */
    protected function initCurl()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return $curl;
    }

    /**
     * Fixes the headers to match the cUrl format and set them.
     *
     * @param resource $curl    The curl resource.
     * @param array    $headers An array of headers.
     */
    protected function setHeaders($curl, array $headers)
    {
        $fixedHeaders = array();

        foreach ($headers as $key => $value) {
            if (is_int($key)) {
                $fixedHeaders[] = $value;
            } else {
                $fixedHeaders[] = $key.':'.$value;
            }
        }

        if (!empty($fixedHeaders)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $fixedHeaders);
        }
    }
}
