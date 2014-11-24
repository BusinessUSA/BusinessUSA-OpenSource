
This module provides support for more advanced authentication mechanisms
using the Drupal Feeds module.  http://drupal.org/project/feeds

Most notably, this can be used for feeds requiring a 2-step authentication
where the first request passes authentication credentials, and then the
server returns a form of security tokens to be passed in future reqeusts.

The credentials, and return token keys to look for is fully configurable
in the Fetcher settings for your Feed.  The communication protocol is also
configurable, plain &url=var and json posting is supported currently.

It is reccomended to use this along with the Feeds JSONPath Parser
http://drupal.org/project/feeds_jsonpath_parser
when using json returns.

----------
Features

Test mode:
- Currently only sets the curl_setopt value "CURLOPT_SSL_VERIFYPEER" to "FALSE".

Authentication methods:
- Plain: Passes credentials through URL.
    e.g.:  https://example.com/login?user=username&pass=password
- JSON: Performs a json_encode()'d post of the credentials.

Works with or without cURL!

----------
Usage

If there is a webservice or feed you wish Drupal to pull, and it requires:
- Authentication in the form of JSON POST.
- "2-step authentication", where you first pass login credentials, and then
    security tokens are returned that you must pass on subsequent calls.
