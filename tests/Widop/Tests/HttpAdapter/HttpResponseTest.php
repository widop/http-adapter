<?php

/*
 * This file is part of the Wid'op package.
 *
 * (c) Wid'op <contact@widop.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Widop\Tests\HttpAdapter;

use Widop\HttpAdapter\HttpResponse;

/**
 * Http response test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class HttpResponseTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Widop\HttpAdapter\HttpResponse */
    protected $httpResponse;

    /** @var integer */
    protected $statusCode;

    /** @var string */
    protected $url;

    /** @var array */
    protected $headers;

    /** @var string */
    protected $body;

    /** @var string */
    protected $effectiveUrl;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->statusCode = 200;
        $this->url = 'url';
        $this->headers = array('foo' => 'bar');
        $this->body = 'body';
        $this->effectiveUrl = 'effective_url';

        $this->httpResponse = new HttpResponse($this->statusCode, $this->url, $this->headers, $this->body, $this->effectiveUrl);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->statusCode);
        unset($this->effectiveUrl);
        unset($this->body);
        unset($this->headers);
        unset($this->url);
        unset($this->httpResponse);
    }

    public function testStatusCode()
    {
        $this->assertSame($this->statusCode, $this->httpResponse->getStatusCode());
    }

    public function testUrl()
    {
        $this->assertSame($this->url, $this->httpResponse->getUrl());
    }

    public function testHeaders()
    {
        $this->assertSame($this->headers, $this->httpResponse->getHeaders());
    }

    public function testHeaderWithExistingHeader()
    {
        $this->assertSame($this->headers['foo'], $this->httpResponse->getHeader('foo'));
    }

    public function testHeaderWithCaseSensititvity()
    {
        $this->assertSame($this->headers['foo'], $this->httpResponse->getHeader('FoO'));
    }

    public function testHeaderWithNoExistingHeader()
    {
        $this->assertNull($this->httpResponse->getHeader('bar'));
    }

    public function testBody()
    {
        $this->assertSame($this->body, $this->httpResponse->getBody());
    }

    public function testEffectiveUrl()
    {
        $this->assertSame($this->effectiveUrl, $this->httpResponse->getEffectiveUrl());
    }
}
