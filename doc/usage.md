# Usage

## Create your Adapter

In order to make an http request, you need to create an adapter.

### cURL

``` php
use Widop\HttpAdapter\CurlHttpAdapter;

$curlHttpAdapter = new CurlHttpAdapter();
```

### Stream

``` php
use Widop\HttpAdapter\StreamHttpAdapter;

$streamHttpAdapter = new StreamHttpAdapter();
```

### Buzz

``` php
use Widop\HttpAdapter\BuzzHttpAdapter;

$buzzHttpAdapter = new BuzzHttpAdapter();
```

or

``` php
use Buzz\Browser;
use Widop\HttpAdapter\BuzzHttpAdapter;

$browser = new Browser();
$buzzHttpAdapter = new BuzzHttpAdapter($browser);
```

### Guzzle

``` php
use Widop\HttpAdapter\GuzzleHttpAdapter;

$guzzleHttpAdapter = new GuzzleHttpAdapter();
```

or

``` php
use Guzzle\Http\Client;
use Widop\HttpAdapter\GuzzleHttpAdapter;

$client = new Client();
$guzzleHttpAdapter = new GuzzleHttpAdapter($client);
```

## Zend

``` php
use Widop\HttpAdapter\ZendHttpAdapter;

$zendHttpAdapter = new ZendHttpAdapter();
```

or

``` php
use Widop\HttpAdapter\ZendHttpAdapter;
use Zend\Http\Client;

$client = new Client();
$zendHttpAdapter = new ZendHttpAdapter($client);
```

## Make a GET request

Each adapter allows you to make a GET request:

``` php
$response = $httpAdapter->getContent($url);
```

If you want to pass custom headers, you can use the second argument:

``` php
$response = $httpAdapter->getContent($url, $headers);
```

You may want to use persistent callbacks (if you want to download large file, listen to an API endpoint ...).
Simply use the third argument:

``` php
$content = $httpAdapter->getContent($url, $headers, array($this, 'writeLargeFile');
```

*There are a few things you need to know about persistent callbacks:*
 - This method is supported by all adapters except the Zend one.
 - It will only works on Buzz if you use the Curl client (or an implementation of the Curl client supporting
   `->setOption` calls).

## Make a POST request

Each adapter allows you to make a POST request:

``` php
$response = $httpAdapter->postContent($url);
```

If you want to pass custom headers, you can use the second argument:

``` php
$response = $httpAdapter->postContent($url, $headers);
```

If you want to pass POST datas, you can use the third argument:

``` php
$response = $httpAdapter->postContent($url, $headers, $data);
```

If you want to pass POST files, you can use the fourth argument:

``` php
$response = $httpAdapter->postContent($url, $headers, $data, $files);
```

## Inspect the response

All http adapter methods return a `Widop\HttpAdapter\Response` object which wraps the base URL, the body and the
effective URL.

``` php
$url = $response->getUrl();
$body = $response->getBody();
$effectiveUrl = $response->getEffectiveUrl();
```

Be aware that the effective URL is supported by most of the adapters except Buzz and Zend ones...
