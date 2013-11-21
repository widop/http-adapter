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

use Buzz\Browser;

/**
 * Buzz Http adapter.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class BuzzHttpAdapter extends AbstractHttpAdapter
{
    /** @var \Buzz\Browser */
    private $browser;

    /**
     * Constructor.
     *
     * @param \Buzz\Browser $browser      The buzz browser.
     * @param integer       $maxRedirects The maximum redirects.
     */
    public function __construct(Browser $browser = null, $maxRedirects = 5)
    {
        parent::__construct($maxRedirects);

        if ($browser === null) {
            $browser = new Browser();
        }

        $this->browser = $browser;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent($url, array $headers = array(), $persistentCallback = null)
    {
        $this->configure($persistentCallback);

        try {
            return $this->createResponse($url, $this->browser->get($url, $headers)->getContent());
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postContent(
        $url,
        array $headers = array(),
        array $content = array(),
        array $files = array(),
        $persistentCallback = null
    ) {
        $this->configure($persistentCallback);
        $post = $content;

        if (!empty($files)) {
            if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
                $post = array_merge($post, array_map(function($file) { return new \CURLFile($file); }, $files));
            } else {
                $post = array_merge($post, array_map(function($file) { return '@'.$file; }, $files));
            }
        }

        try {
            return $this->createResponse($url, $this->browser->post($url, $headers, $post)->getContent());
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'buzz';
    }

    /**
     * Configures the buzz browser.
     *
     * @param callable $persistentCallback The persistent callback.
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If client is not supported for persistent callback.
     */
    private function configure($persistentCallback)
    {
        $client = $this->browser->getClient();

        if ($persistentCallback !== null) {
            $client->setOption(CURLOPT_WRITEFUNCTION, $this->createCurlPersistentCallback($persistentCallback));
        }

        $client->setMaxRedirects($this->getMaxRedirects());
    }
}
