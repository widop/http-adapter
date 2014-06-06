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

/**
 * Abstract http adapter test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractHttpAdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Widop\HttpAdapter\HttpAdapterInterface */
    protected $httpAdapter;

    /** @var string */
    protected $url;

    /** @var array */
    protected $headers;

    /** @var array */
    protected $content;

    /** @var array */
    protected $files;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->statusCode = 200;
        $this->url = 'http://www.widop.com';
        $this->headers = array('Accept-Charset' => 'utf-8', 'Accept-Language: en-US,en;q=0.8');
        $this->content = array('param' => 'value');
        $this->files = array('file' => realpath(__DIR__.'/Fixtures/file.txt'));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->files);
        unset($this->content);
        unset($this->headers);
        unset($this->url);
        unset($this->httpAdapter);
    }

    /**
     * Asserts an http response.
     *
     * @param mixed $response The http response.
     */
    protected function assertResponse($response)
    {
        $this->assertInstanceOf('Widop\HttpAdapter\HttpResponse', $response);
        $this->assertSame($this->statusCode, $response->getStatusCode());
        $this->assertSame($this->url, $response->getUrl());
        $this->assertNotEmpty($response->getHeaders());
        $this->assertNotEmpty($response->getBody());
    }

    abstract public function testName();

    public function testGetContentWithoutHeaders()
    {
        $this->assertResponse($this->httpAdapter->getContent($this->url));
    }

    public function testGetContentWithHeaders()
    {
        $this->assertResponse($this->httpAdapter->getContent($this->url,$this->headers));
    }

    /**
     * @expectedException \Widop\HttpAdapter\HttpAdapterException
     */
    public function testGetContentWithInvalidUrl()
    {
        $this->httpAdapter->getContent('foo');
    }

    public function testPostContentWithoutHeaders()
    {
        $this->assertResponse($this->httpAdapter->postContent($this->url));
    }

    public function testPostContentWithHeaders()
    {
        $this->assertResponse($this->httpAdapter->postContent($this->url, $this->headers));
    }

    public function testPostContentWithHeadersAndContent()
    {
        $this->assertResponse($this->httpAdapter->postContent($this->url, $this->headers, $this->content));
    }

    public function testPostContentWithHeadersAndContentAndFiles()
    {
        $this->assertResponse($this->httpAdapter->postContent(
            $this->url,
            $this->headers,
            $this->content,
            $this->files
        ));
    }

    /**
     * @expectedException \Widop\HttpAdapter\HttpAdapterException
     */
    public function testPostContentWithInvalidUrl()
    {
        $this->httpAdapter->postContent('foo');
    }

    public function testHead()
    {
        $response = $this->httpAdapter->head($this->url, $this->headers);

        $this->assertInstanceOf('Widop\HttpAdapter\HttpResponse', $response);
        $this->assertSame($this->statusCode, $response->getStatusCode());
        $this->assertSame($this->url, $response->getUrl());
        $this->assertNotEmpty($response->getHeaders());
    }

    public function testPutContentWithHeadersAndContent()
    {
        $this->assertResponse($this->httpAdapter->put(
            $this->url,
            $this->headers,
            $this->content
        ));
    }

    public function testPutContentWithHeadersAndContentAndFiles()
    {
        $this->assertResponse($this->httpAdapter->put(
            $this->url,
            $this->headers,
            $this->content,
            $this->files
        ));
    }
}
