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
 * Http adapter interface.
 *
 * Headers may be passed using:
 * - an indexed array (0 => 'Content-Type: html/text')
 * - an associative array ('Content-Type' => 'html/text')
 * - a mix of the two
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface HttpAdapterInterface
{
    /**
     * Gets the content fetched from the given URL.
     *
     * @param string $url     The URL to request.
     * @param array  $headers HTTP headers (optionnal).
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return string The fetched content.
     */
    function getContent($url, array $headers = array());

    /**
     * Gets the content fetched from the given url & POST datas.
     *
     * @param string       $url     The URL to request.
     * @param array        $headers HTTP headers (optional).
     * @param string|array $content The POST content (optional).
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return string The fetched content.
     */
    function postContent($url, array $headers = array(), $content = '');

    /**
     * Gets the name of the Http adapter.
     *
     * @return string The Http adapter name.
     */
    function getName();
}
