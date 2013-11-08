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
$content = $httpAdapter->getContent($url);
```

If you want to pass custom headers, you can use the second argument:

``` php
$content = $httpAdapter->getContent($url, $headers);
```

## Make a POST request

Each adapter allows you to make a POST request:

``` php
$content = $httpAdapter->postContent($url);
```

If you want to pass custom headers, you can use the second argument:

``` php
$content = $httpAdapter->postContent($url, $headers);
```

If you want to pass POST datas, you can use the third argument:

``` php
$content = $httpAdapter->postContent($url, $headers, $data);
```

If you want to pass POST files, you can use the fourth argument:

``` php
$content = $httpAdapter->postContent($url, $headers, $data, $files);
```
