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
 * @author GeLo <geloen.eric@gmail.com>
 */
interface HttpAdapterInterface
{
    /**
     * Gets the content fetched from the given URL.
     *
     * @param string $url     The url to request.
     * @param array  $headers The http headers (optional).
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return \Widop\HttpAdapter\HttpResponse The http response.
     */
    function getContent($url, array $headers = array());

    /**
     * Gets the content fetched from the given url & POST datas.
     *
     * @param string $url     The url to request.
     * @param array  $headers The http headers (optional).
     * @param array  $content The post content (optional).
     * @param array  $files   The post files (optional).
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return \Widop\HttpAdapter\HttpResponse The http response.
     */
    function postContent($url, array $headers = array(), array $content = array(), array $files = array());

    /**
     * Performs a HEAD request.
     *
     * @param string $url     The url to request.
     * @param array  $headers The http headers (optional).
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return \Widop\HttpAdapter\HttpResponse The http response.
     */
    function head($url, array $headers = array());

    /**
     * Performs a PUT request.
     *
     * @param string $url     The url to request.
     * @param array  $headers The http headers (optional).
     * @param array  $content The post content (optional).
     * @param array  $files   The post files (optional).
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return \Widop\HttpAdapter\HttpResponse The http response.
     */
    function put($url, array $headers = array(), array $content = array(), array $files = array());

    /**
     * Gets the name of the http adapter.
     *
     * @return string The http adapter name.
     */
    function getName();
}
