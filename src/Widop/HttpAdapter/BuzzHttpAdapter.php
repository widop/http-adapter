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
use Buzz\Message\Form\FormUpload;

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
    public function getContent($url, array $headers = array())
    {
        return $this->sendRequest($url, 'GET', $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function postContent($url, array $headers = array(), array $content = array(), array $files = array())
    {
        return $this->sendRequest($url, 'POST', $headers, $content, $files);
    }

    /**
     * {@inheritdoc}
     */
    public function head($url, array $headers = array())
    {
        return $this->sendRequest($url, 'HEAD', $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, array $headers = array(), array $content = array(), array $files = array())
    {
        return $this->sendRequest($url, 'PUT', $headers, $content, $files);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url, array $headers = array(), array $content = array(), array $files = array())
    {
        return $this->sendRequest($url, 'DELETE', $headers, $content, $files);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'buzz';
    }

    /**
     * Sends a request.
     *
     * @param string $url     The url.
     * @param string $method  The http method.
     * @param array  $headers The header (optional).
     * @param array  $content The content (optional).
     * @param array  $files   The files (optional).
     *
     * @throws \Widop\HttpAdapter\HttpAdapterException If an error occured.
     *
     * @return \Widop\HttpAdapter\HttpResponse The response.
     */
    private function sendRequest($url, $method, array $headers = array(), array $content = array(), array $files = array())
    {
        $this->browser->getClient()->setMaxRedirects($this->getMaxRedirects());

        try {
            if (!empty($files) && class_exists('Buzz\Message\Form\FormUpload')) {
                $response = $this->browser->submit(
                    $url,
                    $this->mergeFilesAndContent($content, $files, function ($file) {
                        return new FormUpload($file);
                    }),
                    $method,
                    $headers
                );
            } else {
                $response = $this->browser->call(
                    $url,
                    $method,
                    $headers,
                    $this->mergeFilesAndContent($content, $files, function ($file) {
                        return '@'.$file;
                    })
                );
            }
        } catch (\Exception $e) {
            throw HttpAdapterException::cannotFetchUrl($url, $this->getName(), $e->getMessage());
        }

        return $this->createResponse(
            $response->getStatusCode(),
            $url,
            $response->getHeaders(),
            $response->getContent()
        );
    }

    /**
     * Merges the content and files data.
     *
     * @param array    $content               The content.
     * @param array    $files                 The files.
     * @param callable $transformFileCallback The callback responsible to modify a file.
     *
     * @return array The merged content.
     */
    private function mergeFilesAndContent(array $content, array $files, $transformFileCallback)
    {
        $fields = array();

        foreach ($files as $key => $file) {
            if (is_int($key)) {
                $fields[] = $transformFileCallback($file);
            } else {
                $fields[$key] = $transformFileCallback($file);
            }
        }

        foreach ($content as $key => $data) {
            if (is_int($key)) {
                $fields[] = $data;
            } else {
                $fields[$key] = $data;
            }
        }

        return $fields;
    }
}
