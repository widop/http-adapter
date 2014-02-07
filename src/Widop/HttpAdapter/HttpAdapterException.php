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
 * Http adapter exception.
 *
 * @author Geoffrey Brier <geoffrey.brier@gmail.com>
 */
class HttpAdapterException extends \Exception
{
    /**
     * Gets the "CANNOT FETCH URL" exception.
     *
     * @param string $url     The URL.
     * @param string $adapter The adapter name.
     * @param string $info    Additional informations about the error.
     *
     * @return \Widop\HttpAdapterBundle\Exception\HttpAdapterException An exception.
     */
    public static function cannotFetchUrl($url, $adapter, $info)
    {
        return new self(sprintf(
            'An error occured when fetching the URL "%s" with the adapter "%s" ("%s").',
            $url,
            $adapter,
            $info
        ));
    }
}
