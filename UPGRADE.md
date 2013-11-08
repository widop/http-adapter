# UPGRADE

## 1.0.2 to 1.1.0

 * The third argument of the `HttpAdapterInterface::postContent` (ie. `$content`) is now typehinted as `array`
   in order to be consistent with other parameters.
 * The third argument of the `CurlHttpAdapter::execute` (ie. `$content`) is now typehinted as `array` in order to be
   consistent with other parameters.
