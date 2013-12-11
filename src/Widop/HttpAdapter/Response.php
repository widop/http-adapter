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
 * Response.
 *
 * @author Dudu <clement.duez@widop.com>
 */
class Response
{
    /** @var string */
    private $body;

    /** @var string */
    private $url;

    /** @var string */
    private $effectiveUrl;

    /**
     * Gets the body.
     *
     * @return string The body.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets the body.
     *
     * @param string $body The body.
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Gets the url.
     *
     * @return string The url.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the url.
     *
     * @param string $url The url.
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Gets the effective url.
     *
     * @return string The effective url.
     */
    public function getEffectiveUrl()
    {
        return $this->effectiveUrl;
    }

    /**
     * Sets the effective url.
     *
     * @param string $effectiveUrl The effective url.
     */
    public function setEffectiveUrl($effectiveUrl)
    {
        $this->effectiveUrl = $effectiveUrl;
    }
}
