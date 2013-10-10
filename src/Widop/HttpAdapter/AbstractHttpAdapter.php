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
}
