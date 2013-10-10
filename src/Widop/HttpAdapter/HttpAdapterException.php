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
     * Gets the "Cannot fetch URL ..." exception.
     *
     * @param string $url     An URL.
     * @param string $adapter The adapter name.
     * @param string $info    Additional information about the error.
     *
     * @return \Widop\HttpAdapterBundle\Exception\HttpAdapterException An exception.
     */
    public static function cannotFetchUrl($url, $adapter, $info)
    {
        return new self(
            sprintf('Cannot fetch URL "%s" with adapter "%s" ("%s").', $url, $adapter, $info)
        );
    }
}
