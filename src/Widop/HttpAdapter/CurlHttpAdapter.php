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
    public function postContent($url, array $headers = array(), array $content = array(), array $files = array())
    {
        $fixedContent = $this->fixContent($content);

        return $this->execute($url, $headers, $content, function ($curl) use ($content, $files, $fixedContent) {
            if (!empty($files)) {
                if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
                    $post = array_merge($content, array_map(function($file) { return new \CURLFile($file); }, $files));
                } else {
                    $post = array_merge($content, array_map(function($file) { return '@'.$file; }, $files));
                }
            } else {
                $post = $fixedContent;
            }

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        });
    }

    /**
     * Fetches a response from an URL.
     *
     * @param string   $url      A valid URL.
     * @param array    $headers  Http headers.
     * @param array    $content  Http content (in case of POST method).
     * @param callable $callback A callable function.
     *
     * @return \Widop\HttpAdapter\Response The response.
     */
    protected function execute($url, array $headers = array(), array $content = array(), $callback = null)
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
        $lastRequestUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

        if ($content === false) {
            $error = curl_error($curl);

            curl_close($curl);

            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $error);
        }

        curl_close($curl);

        return $this->createResponse($url, $content, $lastRequestUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'curl';
    }
}
