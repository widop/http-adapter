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
class CurlHttpAdapter extends AbstractHttpAdapter
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
        $content = $this->fixContent($content);

        return $this->execute($url, $headers, $content, function ($curl) use ($content) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
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
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->fixUrl($url));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($this->getMaxRedirects() > 0) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS, $this->getMaxRedirects());
        }

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->fixHeaders($headers));
        }

        if ($callback !== null) {
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
}
