<?php

/**
 * @file
 * Home of the FeedsHTTPFetcherAA and related classes.
 */

/**
 * Result of FeedsHTTPFetcherAAResult::fetch().
 */
class FeedsHTTPFetcherAAResult extends FeedsFetcherResult {
  protected $url;
  protected $authPostFormat;
  protected $authCredentials;
  protected $acceptInvalidCert;

  /**
   * Constructor.
   */
  public function __construct($url = NULL, $auth_post_format = 'plain', $auth_credentials = array(), $accept_invalid_cert = FALSE) {
    $this->url = $url;
    $this->authPostFormat = $auth_post_format;
    $this->authCredentials = $auth_credentials;
    $this->acceptInvalidCert = $accept_invalid_cert;
    parent::__construct('');
  }

  /**
   * Overrides FeedsFetcherResult::getRaw().
   */
  public function getRaw() {
    feeds_include_library('http_request.inc', 'http_request');
    $auth_inc_path = drupal_get_path('module', 'feeds_httpfetcher_aa') . '/libraries/feeds_httpfetcher_aa_request.inc';
    require_once $auth_inc_path;
    // These are the same for now, but good preparation for future protocols.
    switch ($this->authPostFormat) {
      case 'json':
        $result = feeds_httpfetcher_aa_request_get($this->url, $this->authPostFormat, $this->authCredentials, $this->acceptInvalidCert);
        break;

      default:
        // 'plain'
        $result = feeds_httpfetcher_aa_request_get($this->url, $this->authPostFormat, $this->authCredentials, $this->acceptInvalidCert);
        break;
    }
    if (!in_array($result->code, array(200, 201, 202, 203, 204, 205, 206))) {
      throw new Exception(t('Download of @url failed with code !code.', array('@url' => $this->url, '!code' => $result->code)));
    }

    return $this->sanitizeRaw($result->data);
  }
}

/**
 * Fetches data via HTTP.
 */
class FeedsHTTPFetcherAA extends FeedsFetcher {

  /**
   * Implements FeedsFetcher::fetch().
   */
  public function fetch(FeedsSource $source) {
    $auth_inc_path = drupal_get_path('module', 'feeds_httpfetcher_aa') . '/libraries/feeds_httpfetcher_aa_request.inc';
    require_once $auth_inc_path;
    feeds_include_library('http_request.inc', 'http_request');

    $source_config = $source->getConfigFor($this);
    $accept_invalid_cert = FALSE;
    $auth_credentials = array();
    if ($this->config['test_mode']) {
      $accept_invalid_cert = TRUE;
    }
    $auth_credentials = $this->config['auth_credentials'];
    $auth_post_format = $this->config['auth_post_format'];

    if (!$this->config['two_step_auth']) {
      return new FeedsHTTPFetcherAAResult(
        $source_config['source'],
        $auth_post_format,
        $auth_credentials,
        $accept_invalid_cert
      );
    }

    if ($this->config['two_step_auth'] && !empty($this->config['login_url'])) {
      $auth_result = feeds_httpfetcher_aa_auth_request_get(
        $this->config['login_url'],
        $auth_post_format,
        $auth_credentials,
        $accept_invalid_cert
      );

      if (!empty($auth_result->auth)) {
        switch ($auth_post_format) {
          case 'plain':
            foreach ($this->config['auth_tokens'] as $auth_key => $token_key) {
              if (!empty($auth_result->auth->{$auth_key})) {
                $source_config['source'] = $source_config['source'] . '&' . $token_key . '=' . $auth_result->auth->{$auth_key};
              }
            }
            if (!strpos($source_config['source'], '?')) {
              $source_config['source'] = preg_replace('/\&/', '?', $source_config['source'], 1);
            }
            break;

          // Prepare json fetch with obtained credentials.
          case 'json':
            unset($auth_credentials);
            foreach ($this->config['auth_tokens'] as $auth_key => $token_key) {
              if (!empty($auth_result->auth->{$auth_key})) {
                $auth_credentials[$token_key] = $auth_result->auth->{$auth_key};
              }
            }
            break;

        }
      }
    }
    else {
      // If they forgot to enter login URL, stop the import, respond with a 503.
      drupal_set_message(t('You must configure a login URL when using HTTP AA fetcher 2-step authentication.'), 'error', FALSE);
      drupal_add_http_header('Status', '503 Service unavailable');
      drupal_exit();
    }

    return new FeedsHTTPFetcherAAResult(
        $source_config['source'],
        $auth_post_format,
        $auth_credentials,
        $accept_invalid_cert
      );
  }

  /**
   * Clear caches.
   */
  public function clear(FeedsSource $source) {
    $source_config = $source->getConfigFor($this);
    $url = $source_config['source'];
    feeds_include_library('http_request.inc', 'http_request');
    http_request_clear_cache($url);
  }

  /**
   * Implements FeedsFetcher::request().
   */
  public function request($feed_nid = 0) {
    feeds_dbg($_GET);
    @feeds_dbg(file_get_contents('php://input'));
    try {
      feeds_source($this->id, $feed_nid)->existing()->import();
    }
    catch (Exception $e) {
      // In case of an error, respond with a 503.
      drupal_add_http_header('Status', '503 Service unavailable');
      drupal_exit();
    }

    // Will generate the default 200 response.
    drupal_add_http_header('Status', '200 OK');
    drupal_exit();
  }

  /**
   * Override parent::configDefaults().
   */
  public function configDefaults() {
    return array(
      'auto_detect_feeds' => FALSE,
      'test_mode' => FALSE,
      'auth_post_format' => 'plain',
      'auth_credentials' => array('user' => 'drupal', 'pass' => 'password'),
      'two_step_auth' => FALSE,
      'login_url' => '',
      'auth_tokens' => array('id' => 'id'),
    );
  }

  /**
   * Override parent::configForm().
   */
  public function configForm(&$form_state) {
    $form = array();
    $form['auto_detect_feeds'] = array(
      '#type' => 'checkbox',
      '#title' => t('Auto detect feeds'),
      '#description' => t('If the supplied URL does not point to a feed but an HTML document, attempt to extract a feed URL from the document.'),
      '#default_value' => $this->config['auto_detect_feeds'],
    );
    $form['test_mode'] = array(
      '#type' => 'checkbox',
      '#title' => t('Test mode'),
      '#description' => t('Applies certain settings for test mode.  e.g. sets CURLOPT_SSL_VERIFYPEER FALSE'),
      '#default_value' => $this->config['test_mode'],
    );
    $form['auth_post_format'] = array(
      '#type' => 'select',
      '#title' => t('Authentication format.'),
      '#description' => t('Enter the protocol for the credentials to be sent to the login URL.'),
      '#options' => array(
        'json' => 'JSON',
        'plain' => 'Plain (&URL=Vars)',
      ),
      '#default_value' => $this->config['auth_post_format'],
    );
    $form['auth_credentials'] = array(
      '#type' => 'textarea',
      '#title' => t('Authentication credentials'),
      '#description' => t('Enter (one per line) a key|value pair of authentication credentials.  e.g. email|example@example.com'),
      '#default_value' => list_allowed_values_string($this->config['auth_credentials']),
      '#rows' => 5,
      '#element_validate' => array('list_allowed_values_setting_validate'),
      '#field_has_data' => FALSE,
      '#field' => array('type' => 'list_text'),
      '#field_type' => 'list_text',
      '#dependency' => array(
        'edit-auth-post-format' => array('json'),
      ),
    );
    $form['two_step_auth'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use 2-step authentication'),
      '#description' => t('Does your feed return security tokens after logging in that you need to pass in subsequent requests?'),
      '#default_value' => $this->config['two_step_auth'],
    );
    $form['login_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Feed Login URL'),
      '#description' => t('Enter the full URL for the authentication request that will return tokens/ids needed for future feed requests.  (Include credentials in URL if using plain authentication)  e.g. https://example.com/login or https://example.com?user=drupal&pass=password'),
      '#default_value' => $this->config['login_url'],
      '#dependency' => array(
        'edit-two-step-auth' => array(1),
      ),
    );
    $form['auth_tokens'] = array(
      '#type' => 'textarea',
      '#title' => t('Authentication response fields (token names)'),
      '#description' => t('Enter (one per line) a key|value pair of the field_names to look for in the feed authentication response.  The Key is what the login response uses as key, the Value is what future requests will use as key.  These can be the same or different depending on the feed.  e.g. session_id|session_id or id|user_id'),
      '#default_value' => list_allowed_values_string($this->config['auth_tokens']),
      '#rows' => 5,
      '#element_validate' => array('list_allowed_values_setting_validate'),
      '#field_has_data' => FALSE,
      '#field' => array('type' => 'list_text'),
      '#field_type' => 'list_text',
      '#dependency' => array(
        'edit-two-step-auth' => array(1),
      ),
    );

    return $form;
  }

  /**
   * Expose source form.
   */
  public function sourceForm($source_config) {
    $form = array();
    $form['source'] = array(
      '#type' => 'textfield',
      '#title' => t('URL'),
      '#description' => t('Enter a feed URL.  This is the actual query/data request.
        The authentication URL should be configured in the !url',
        array('!url' => l(t('Feeds Fetcher settings'), 'admin/structure/feeds'))),
      '#default_value' => isset($source_config['source']) ? $source_config['source'] : '',
      '#maxlength' => NULL,
      '#required' => TRUE,
    );
    return $form;
  }

  /**
   * Override parent::sourceFormValidate().
   */
  public function sourceFormValidate(&$values) {
    $values['source'] = trim($values['source']);

    if (!feeds_valid_url($values['source'], TRUE)) {
      $form_key = 'feeds][' . get_class($this) . '][source';
      form_set_error($form_key, t('The URL %source is invalid.', array('%source' => $values['source'])));
    }
    elseif ($this->config['auto_detect_feeds']) {
      feeds_include_library('http_request.inc', 'http_request');
      if ($url = http_request_get_common_syndication($values['source'])) {
        $values['source'] = $url;
      }
    }

  }
}
