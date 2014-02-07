# UPGRADE

## 1.0 to 1.1

 * The `Widop\HttpAdapter\HttpAdapterInterface::getContent` and the
   `Widop\HttpAdapter\HttpAdapterInterface::postContent` returns a `Widop\HttpAdapter\HttpResponse` instead of the
   response body string.
 * The third argument of the `Widop\HttpAdapter\HttpAdapterInterface::postContent` (`$content`) is now typehinted as
   `array` in order to be consistent with other parameters.
 * All protected properties/methods have been updated to private except for explicit entry points.
