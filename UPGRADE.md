# UPGRADE

## 1.0.2 to 1.1.0

 * The `HttpAdapterInterface::getContent` and the `HttpAdapterInterface::postContent` now returns a
   `Widop\HttpAdapter\Response` object instead of the response body string.
 * The `BuzzHttpAdapter::execute` now returns a `Widop\HttpAdapter\Response` object instead of the response body
   string.
 * The `StreamHttpAdapter::execite` now returns a `Widop\HttpAdapter\Response` object instead of the response body
   string.
 * The third argument of the `HttpAdapterInterface::postContent` (ie. `$content`) is now typehinted as `array`
   in order to be consistent with other parameters.
 * The third argument of the `CurlHttpAdapter::execute` (ie. `$content`) is now typehinted as `array` in order to be
   consistent with other parameters.
