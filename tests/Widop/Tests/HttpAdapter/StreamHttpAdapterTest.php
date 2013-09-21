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

use Widop\HttpAdapter\StreamHttpAdapter;

/**
 * Stream http adapter test.
 *
 * @author Geoffrey Brier <geoffrey.brier@gmail.com>
 */
class StreamHttpAdapterTest extends AbstractHttpAdapterTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->httpAdapter = new StreamHttpAdapter();
    }

    public function testName()
    {
        $this->assertSame('stream', $this->httpAdapter->getName());
    }

    /**
     * @expectedException \Widop\HttpAdapter\HttpAdapterException
     */
    public function testExtractMethodWithEmptyHeaderThrowsException()
    {
        $this->getHttpAdapterReflectionMethod('extractHeaderKeyAndValue')
            ->invokeArgs($this->httpAdapter, array(''));
    }

    /**
     * @expectedException \Widop\HttpAdapter\HttpAdapterException
     */
    public function testExtractMethodWithInvalidHeaderThrowsException()
    {
        $this->getHttpAdapterReflectionMethod('extractHeaderKeyAndValue')
            ->invokeArgs($this->httpAdapter, array('foo'));
    }

    /**
     * @expectedException \Widop\HttpAdapter\HttpAdapterException
     */
    public function testExtractMethodWithEmptyHeaderKeyThrowsException()
    {
        $this->getHttpAdapterReflectionMethod('extractHeaderKeyAndValue')
            ->invokeArgs($this->httpAdapter, array(': 42\r\n'));
    }

    /**
     * @expectedException \Widop\HttpAdapter\HttpAdapterException
     */
    public function testExtractMethodWithEmptyHeaderValueThrowsException()
    {
        $this->getHttpAdapterReflectionMethod('extractHeaderKeyAndValue')
            ->invokeArgs($this->httpAdapter, array('Content-Length:'));
    }

    public function testExtractMethodReturnsCorrectValues()
    {
        list ($hKey, $hValue) = $this->getHttpAdapterReflectionMethod('extractHeaderKeyAndValue')
            ->invokeArgs($this->httpAdapter, array("Content-Length: 42\r\n"));

        $this->assertEquals('Content-Length', $hKey);
        $this->assertEquals('42', $hValue);
    }

    public function testHeaderKeyMatchesIsCaseInsensitive()
    {
        $doMatch = $this->getHttpAdapterReflectionMethod('headerKeyMatches')
            ->invokeArgs($this->httpAdapter, array(array('Content-Length' => '42'), 'CoNtenT-LenGth'));

        $this->assertTrue($doMatch);
    }

    public function testHeaderKeyMatchesReturnsFalse()
    {
        $doMatch = $this->getHttpAdapterReflectionMethod('headerKeyMatches')
            ->invokeArgs($this->httpAdapter, array(array('Content-type' => 'html/css'), 'CoNtenT-LenGth'));

        $this->assertFalse($doMatch);

    }

    public function testFixUrlAddsHttpSchemeIfNotPresent()
    {
        $url = $this->getHttpAdapterReflectionMethod('fixUrl')
            ->invokeArgs($this->httpAdapter, array('www.google.fr'));

        $this->assertEquals('http://www.google.fr', $url);
    }

    public function testFixUrlDoesNotModifyUrlIfSchemePresent()
    {
        $httpUrl = $this->getHttpAdapterReflectionMethod('fixUrl')
            ->invokeArgs($this->httpAdapter, array('http://www.google.fr'));

        $this->assertEquals('http://www.google.fr', $httpUrl);

        $httpsUrl = $this->getHttpAdapterReflectionMethod('fixUrl')
            ->invokeArgs($this->httpAdapter, array('https://www.google.fr'));

        $this->assertEquals('https://www.google.fr', $httpsUrl);
    }
}
