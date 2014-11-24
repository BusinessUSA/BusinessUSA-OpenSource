<?php

/**
 * @file
 * Download via HTTP.
 *
 * Support HTTP Advanced Authentication, as well as detection of RSS/Atom feeds,
 * redirects (via http_request.inc).
 */

/**
 * Get the authentication response from the given login URL.
 *
 * @param string  $url
 *   A valid URL (not only web URLs).
 * @param string  $auth_post_format
 *   The protocol to use for authenticating.
 * @param array   $auth_credentials
 *   An array of key|value credentials to pass for authentication.
 * @param bool $accept_invalid_cert
 *   Whether to accept invalid certificates.
 * @param string  $username
 *   If the URL uses authentication, supply the username.
 * @param string  $password
 *   If the URL uses authentication, supply the password.
 *
 * @return stdClass
 *   An object that describes the data downloaded from $url.
 */
function feeds_httpfetcher_aa_auth_request_get($url, $auth_post_format = 'plain', $auth_credentials = array(), $accept_invalid_cert = FALSE, $username = NULL, $password = NULL) {
  // Intra-pagedownload cache, avoid to download the same content twice within
  // one page download (it's possible, compatible and parse calls).
  static $download_cache = array();
  if (isset($download_cache[$url])) {
    return $download_cache[$url];
  }

  if (!$username && valid_url($url, TRUE)) {
    // Handle password protected feeds.
    $url_parts = parse_url($url);
    if (!empty($url_parts['user'])) {
      $password = $url_parts['pass'];
      $username = $url_parts['user'];
    }
  }

  $curl = http_request_use_curl();
  $headers = array();

  // Support the 'feed' and 'webcal' schemes by converting them into 'http'.
  $url = strtr($url, array('feed://' => 'http://', 'webcal://' => 'http://'));

  if ($curl) {
    $headers[] = 'User-Agent: Drupal (+http://drupal.org/)';
    $result = new stdClass();

    // Parse the URL and make sure we can handle the schema.
    // cURL can only support either http:// or https://.
    // CURLOPT_PROTOCOLS is only supported with cURL 7.19.4
    $uri = parse_url($url);
    if (!isset($uri['scheme'])) {
      $result->error = 'missing schema';
      $result->code = -1002;
    }
    else {
      switch ($uri['scheme']) {
        case 'http':
        case 'https':
          // Valid scheme.
          break;

        default:
          $result->error = 'invalid schema ' . $uri['scheme'];
          $result->code = -1003;
          break;
      }
    }

    // If the scheme was valid, continue to request the feed using cURL.
    if (empty($result->error)) {
      $download = curl_init($url);
      curl_setopt($download, CURLOPT_FOLLOWLOCATION, TRUE);
      if (!empty($username)) {
        curl_setopt($download, CURLOPT_USERPWD, "{$username}:{$password}");
        curl_setopt($download, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      }
      if ($auth_post_format == 'json') {
        $auth_credentials = json_encode($auth_credentials);
        curl_setopt($download, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($download, CURLOPT_POSTFIELDS, $auth_credentials);
        curl_setopt($download, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($auth_credentials))
        );
      }
      else {
        curl_setopt($download, CURLOPT_HTTPHEADER, $headers);
      }
      curl_setopt($download, CURLOPT_HEADER, TRUE);
      curl_setopt($download, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($download, CURLOPT_ENCODING, '');
      curl_setopt($download, CURLOPT_TIMEOUT, variable_get('http_request_timeout', 30));
      if ($accept_invalid_cert) {
        curl_setopt($download, CURLOPT_SSL_VERIFYPEER, 0);
      }
      $header = '';
      $data = curl_exec($download);
      if (curl_error($download)) {
        throw new HRCurlException(
          t('cURL error (@code) @error for @url', array(
            '@code' => curl_errno($download),
            '@error' => curl_error($download),
            '@url' => $url,
          )), curl_errno($download)
        );
      }

      $header_size = curl_getinfo($download, CURLINFO_HEADER_SIZE);
      $header = substr($data, 0, $header_size - 1);
      $result->data = substr($data, $header_size);
      $headers = preg_split("/(\r\n){2}/", $header);
      $header_lines = preg_split("/\r\n|\n|\r/", end($headers));
      $result->headers = array();
      // Skip HTTP response status.
      array_shift($header_lines);

      while ($line = trim(array_shift($header_lines))) {
        list($header, $value) = explode(':', $line, 2);
        // Normalize the headers.
        $header = strtolower($header);

        if (isset($result->headers[$header]) && $header == 'set-cookie') {
          // RFC 2109: the Set-Cookie response header comprises the token Set-
          // Cookie:, followed by a comma-separated list of one or more cookies.
          $result->headers[$header] .= ',' . trim($value);
        }
        else {
          $result->headers[$header] = trim($value);
        }
      }
      $result->code = curl_getinfo($download, CURLINFO_HTTP_CODE);

      curl_close($download);
    }
  }
  else {
    switch ($auth_post_format) {
      case 'json':
        $auth_credentials = json_encode($auth_credentials);
        $options = array(
          'headers' => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($auth_credentials),
          ),
          'method' => 'POST',
          'data' => $auth_credentials,
          'max_redirects' => 3,
          'timeout' => variable_get('http_request_timeout', 30),
          'context' => NULL,
        );
        break;

      default:
        $options = array(
          'headers' => $headers,
          'timeout' => variable_get('http_request_timeout', 30),
        );
        break;
    }
    $result = drupal_http_request($url, $options);
  }

  $result->code = isset($result->code) ? $result->code : 200;

  // In case of 304 Not Modified try to return cached data.
  if ($result->code == 304) {

    if (isset($last_result)) {
      $last_result->from_cache = TRUE;
      return $last_result;
    }
    else {
      // It's a tragedy, this file must exist and contain good data.
      // In this case, clear cache and repeat.
      cache_clear_all('feeds_http_download_' . md5($url), 'cache');
      return feeds_httpfetcher_aa_auth_request_get($url, $auth_post_format, $auth_credentials, $username, $password);
    }
  }

  switch ($auth_post_format) {
    case 'json':
      $result->auth = json_decode($result->data);
      break;

    default:
      // For plain post format.
      $result->auth = $result->data;
      break;
  }

  return $result;
}

/**
 * Make a request using credentials gained from authenticating.
 *
 * @param string  $url
 *   A valid URL (not only web URLs).
 * @param string  $auth_post_format
 *   The protocol to use for authenticating.
 * @param array   $auth_credentials
 *   An array of key|value credentials to pass for authentication.
 * @param bool $accept_invalid_cert
 *   Whether to accept invalid certificates.
 * @param string  $username
 *   If the URL uses authentication, supply the username.
 * @param string  $password
 *   If the URL uses authentication, supply the password.
 *
 * @return stdClass
 *   An object that describes the data downloaded from $url.
 */
function feeds_httpfetcher_aa_request_get($url, $auth_post_format = 'plain', $auth_credentials = array(), $accept_invalid_cert = FALSE, $username = NULL, $password = NULL) {
  // Intra-pagedownload cache, avoid to download the same content twice within
  // one page download (it's possible, compatible and parse calls).
  static $download_cache = array();
  if (isset($download_cache[$url])) {
    return $download_cache[$url];
  }

  if (!$username && valid_url($url, TRUE)) {
    // Handle password protected feeds.
    $url_parts = parse_url($url);
    if (!empty($url_parts['user'])) {
      $password = $url_parts['pass'];
      $username = $url_parts['user'];
    }
  }

  $curl = http_request_use_curl();

  // Only download and parse data if really needs refresh.
  // Based on "Last-Modified" and "If-Modified-Since".
  $headers = array();
  if ($cache = cache_get('feeds_http_download_' . md5($url))) {
    $last_result = $cache->data;
    $last_headers = array_change_key_case($last_result->headers);

    if (!empty($last_headers['etag'])) {
      if ($curl) {
        $headers[] = 'If-None-Match: ' . $last_headers['etag'];
      }
      else {
        $headers['If-None-Match'] = $last_headers['etag'];
      }
    }
    if (!empty($last_headers['last-modified'])) {
      if ($curl) {
        $headers[] = 'If-Modified-Since: ' . $last_headers['last-modified'];
      }
      else {
        $headers['If-Modified-Since'] = $last_headers['last-modified'];
      }
    }
    if (!empty($username) && !$curl) {
      $headers['Authorization'] = 'Basic ' . base64_encode("$username:$password");
    }
  }

  // Support the 'feed' and 'webcal' schemes by converting them into 'http'.
  $url = strtr($url, array('feed://' => 'http://', 'webcal://' => 'http://'));

  if ($curl) {
    $headers[] = 'User-Agent: Drupal (+http://drupal.org/)';
    $result = new stdClass();

    // Parse the URL and make sure we can handle the schema.
    // cURL can only support either http:// or https://.
    // CURLOPT_PROTOCOLS is only supported with cURL 7.19.4
    $uri = parse_url($url);
    if (!isset($uri['scheme'])) {
      $result->error = 'missing schema';
      $result->code = -1002;
    }
    else {
      switch ($uri['scheme']) {
        case 'http':
        case 'https':
          // Valid scheme.
          break;

        default:
          $result->error = 'invalid schema ' . $uri['scheme'];
          $result->code = -1003;
          break;
      }
    }

    // If the scheme was valid, continue to request the feed using cURL.
    if (empty($result->error)) {
      $download = curl_init($url);
      curl_setopt($download, CURLOPT_FOLLOWLOCATION, TRUE);
      if (!empty($username)) {
        curl_setopt($download, CURLOPT_USERPWD, "{$username}:{$password}");
        curl_setopt($download, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      }
      if ($auth_post_format == 'json') {
        $auth_credentials = json_encode($auth_credentials);
        curl_setopt($download, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($download, CURLOPT_POSTFIELDS, $auth_credentials);
        curl_setopt($download, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($auth_credentials))
        );
      }
      else {
        curl_setopt($download, CURLOPT_HTTPHEADER, $headers);
      }
      curl_setopt($download, CURLOPT_HEADER, TRUE);
      curl_setopt($download, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($download, CURLOPT_ENCODING, '');
      curl_setopt($download, CURLOPT_TIMEOUT, variable_get('http_request_timeout', 30));
      if ($accept_invalid_cert) {
        curl_setopt($download, CURLOPT_SSL_VERIFYPEER, 0);
      }
      $header = '';
      $data = curl_exec($download);
      if (curl_error($download)) {
        throw new HRCurlException(
          t('cURL error (@code) @error for @url', array(
            '@code' => curl_errno($download),
            '@error' => curl_error($download),
            '@url' => $url,
          )), curl_errno($download)
        );
      }

      $header_size = curl_getinfo($download, CURLINFO_HEADER_SIZE);
      $header = substr($data, 0, $header_size - 1);
      $result->data = substr($data, $header_size);
      $headers = preg_split("/(\r\n){2}/", $header);
      $header_lines = preg_split("/\r\n|\n|\r/", end($headers));
      $result->headers = array();
      // Skip HTTP response status.
      array_shift($header_lines);

      while ($line = trim(array_shift($header_lines))) {
        list($header, $value) = explode(':', $line, 2);
        // Normalize the headers.
        $header = strtolower($header);

        if (isset($result->headers[$header]) && $header == 'set-cookie') {
          // RFC 2109: the Set-Cookie response header comprises the token Set-
          // Cookie:, followed by a comma-separated list of one or more cookies.
          $result->headers[$header] .= ',' . trim($value);
        }
        else {
          $result->headers[$header] = trim($value);
        }
      }
      $result->code = curl_getinfo($download, CURLINFO_HTTP_CODE);

      curl_close($download);
    }
  }
  else {
    switch ($auth_post_format) {
      case 'json':
        $auth_credentials = json_encode($auth_credentials);
        $options = array(
          'headers' => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($auth_credentials),
          ),
          'method' => 'POST',
          'data' => $auth_credentials,
          'max_redirects' => 3,
          'timeout' => variable_get('http_request_timeout', 30),
          'context' => NULL,
        );
        break;

      default:
        $options = array(
          'headers' => $headers,
          'timeout' => variable_get('http_request_timeout', 30),
        );
        break;
    }
    $result = drupal_http_request($url, $options);
  }

  $result->code = isset($result->code) ? $result->code : 200;

  // In case of 304 Not Modified try to return cached data.
  if ($result->code == 304) {

    if (isset($last_result)) {
      $last_result->from_cache = TRUE;
      return $last_result;
    }
    else {
      // It's a tragedy, this file must exist and contain good data.
      // In this case, clear cache and repeat.
      cache_clear_all('feeds_http_download_' . md5($url), 'cache');
      return feeds_httpfetcher_aa_request_get($url, $auth_post_format, $auth_credentials, $username, $password);
    }
  }

  // Set caches.
  cache_set('feeds_http_download_' . md5($url), $result);
  $download_cache[$url] = $result;
  switch ($auth_post_format) {
    case 'json':
      // JSONPath will decipher a JSON return.
      // @TODO: Possibly offer other return formats.  Support/Feature request.
      // $result->data = json_decode($result->data);
      break;

    default:
      // For plain post format.  nothing for now.
      break;
  }

  return $result;
}
