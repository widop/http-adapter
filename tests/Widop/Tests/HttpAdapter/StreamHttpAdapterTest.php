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
}
