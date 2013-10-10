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

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->httpAdapter);
    }

    abstract public function testName();

    public function testGetContentWithoutHeaders()
    {
        $this->assertNotEmpty($this->httpAdapter->getContent('http://www.google.fr'));
    }

    public function testGetContentWithHeaders()
    {
        $this->assertNotEmpty($this->httpAdapter->getContent(
            'http://www.google.fr',
            array('Accept-Charset' => 'utf-8', 'Accept-Language: en-US,en;q=0.8'))
        );
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
        $this->assertNotEmpty($this->httpAdapter->postContent('http://www.widop.com'));
    }

    public function testPostContentWithHeaders()
    {
        $this->assertNotEmpty($this->httpAdapter->postContent(
            'http://www.widop.com',
            array('Accept-Charset' => 'utf-8', 'Accept-Language: en-US,en;q=0.8'))
        );
    }

    public function testPostContentWithHeadersAndStringData()
    {
        $this->assertNotEmpty(
            $this->httpAdapter->postContent(
                'http://www.widop.com',
                array('Accept-Charset' => 'utf-8', 'Accept-Language: en-US,en;q=0.8'),
                http_build_query(array('param' => 'value'))
            )
        );
    }

    public function testPostContentWithHeadersAndArrayData()
    {
        $this->assertNotEmpty(
            $this->httpAdapter->postContent(
                'http://www.widop.com',
                array('Accept-Charset' => 'utf-8', 'Accept-Language: en-US,en;q=0.8'),
                array('param' => 'value')
            )
        );
    }

    /**
     * @expectedException \Widop\HttpAdapter\HttpAdapterException
     */
    public function testPostContentWithInvalidUrl()
    {
        $this->httpAdapter->postContent('foo');
    }
}
