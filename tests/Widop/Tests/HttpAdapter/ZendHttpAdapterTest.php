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

use Widop\HttpAdapter\ZendHttpAdapter;

/**
 * Zend http adapter test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ZendHttpAdapterTest extends AbstractHttpAdapterTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpAdapter = new ZendHttpAdapter();
    }

    public function testName()
    {
        $this->assertSame('zend', $this->httpAdapter->getName());
    }
}
