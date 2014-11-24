<?php
// $Id: FeedsQueryPathParser.inc,v 1.5.2.6.2.8 2011/02/08 15:40:31 twistor Exp $

/**
 * @file
 *
 * Provides the class for FeedsQueryPathParser.
 */

class FeedsQueryPathParser extends FeedsParser {

  /**
   * Implements FeedsParser::parse().
   */
  public function parse(FeedsSource $source, FeedsFetcherResult $fetcher_result) {
    // Setup mappings so they can be used in variable replacement.
    $mappings = $source->importer->processor->config['mappings'];
    $mappings = $this->filterMappings($mappings);

    // Set source config, if it's empty get config from importer.
    $this->source_config = $source->getConfigFor($this);

    // Allow config inheritance.
    if (empty($this->source_config)) {
      $this->source_config = $this->config;
    }
    $this->rawXML = array_keys(array_filter($this->source_config['rawXML']));
    $this->debug = array_keys(array_filter($this->source_config['debug']['options']));

    $raw = trim($fetcher_result->getRaw());
    if (empty($raw)) {
      throw new Exception(t('Feeds QueryPath parser: The document is empty.'));
    }

    $opts = array('ignore_parser_warnings' => TRUE);
    $result = new FeedsParserResult();
    // Set link so we can set the result link attribute.
    $fetcher_config = $source->getConfigFor($source->importer->fetcher);
    $result->link = $fetcher_config['source'];

    $doc = @qp($raw, NULL, $opts);

    // Convert document to UTF-8
    $ContentType = qp($doc, 'meta[http-equiv="content-type"]');
    if ($ContentType->hasAttr('content') && preg_match('/charset=([-\w]*)/i', $ContentType->attr('content'), $matches)) {
      $ContentType->attr('content', preg_replace('/charset=([-\w]*)/i', 'charset=utf-8', $ContentType->attr('content')));
      qp($doc, 'meta[http-equiv="content-type"]')->remove();
      qp($doc, 'head')->prepend($ContentType->html());
      $doc = qp(drupal_convert_to_utf8(utf8_decode($doc->html()), $matches[1]), NULL, $opts);
    }

    $result->title = qp($doc, 'title', $opts)->text();
    $context = qp($doc, $this->source_config['context'], $opts);
    $this->debug($context, 'context');

    foreach ($context as $item) {
      $parsed_item = $variables = array();
      foreach ($this->source_config['sources'] as $source => $query) {
        $parsed = $this->parseSourceElement($item, $query, $source);
        // Avoid null values.
        if (isset($parsed)) {
          // Variable sunstitution can't handle arrays.
          if (!is_array($parsed)) {
            $variables['{' . $mappings[$source] . '}'] = $parsed;
          }
          else {
            $variables['{' . $mappings[$source] . '}'] = '';
          }
          $parsed_item[$source] = $parsed;
        }
      }
      $result->items[] = $parsed_item;
    }
    return $result;
  }

  protected function parseSourceElement($item, $query, $source) {
    $attr = $this->source_config['attrs'][$source];

    if ($query == '' && $attr == '') {
      return;
    }

    if ($query != '') {
      $item = qp($item, $query);
    }
    $results = array();
    foreach ($item as $k => $i) {
      if ($attr != '') {
        $results[] = $i->attr($attr);
      }
      else if (in_array($source, $this->rawXML)) {
        $results[] = $i->html();
      }
      else {
        $results[] = $i->text();
      }
    }
    $this->debug($results, $source);
    /**
     * If there is one result, return it directly.  If there are no results,
     * return. Otherwise return the results.
     */
    if (count($results) === 1) {
      return $results[0];
    }
    if (count($results) === 0) {
      return;
    }
    return $results;
  }

  /**
   * Source form.
   */
  public function sourceForm($source_config) {
    $form = array();
    // Allow for config inheritance.
    if (empty($source_config)) {
      $source_config = $this->config;
    }
    $mappings_ = feeds_importer($this->id)->processor->config['mappings'];
    $uniques = $mappings = array();

    foreach ($mappings_ as $mapping) {
      if (strpos($mapping['source'], 'querypathparser:') === 0) {
        $mappings[$mapping['source']] = $mapping['target'];
        if ($mapping['unique']) {
          $uniques[] = $mapping['target'];
        }
      }
    }
    $form['querypath'] = array(
      '#type' =>'fieldset',
      '#title' => t('QueryPath Parser Settings'),
      '#tree' => TRUE,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    if (empty($mappings)) {
      $form['querypath']['error_message']['#markup'] = '<div class="help">' . t('FeedsQueryPathParser: No mappings were defined.') . '</div>';
      return $form;
    }
    $form['querypath']['context'] = array(
      '#type' => 'textfield',
      '#title' => t('Context'),
      '#required' => TRUE,
      '#description' => t('This is the base query, all other queries will run in this context.'),
      '#default_value' => isset($source_config['context']) ? $source_config['context'] : '',
      '#maxlength' => 1024,
    );
    $form['querypath']['sources'] = array(
      '#type' => 'fieldset',
    );
    $form['querypath']['attrs'] = array(
      '#title' => t('Attributes'),
      '#type' => 'fieldset',
      '#description' => t('If you want an attribute returned for a field, type its name here.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    if (!empty($uniques)) {
      $items = array(
        format_plural(count($uniques),
          t('Field <strong>!column</strong> is mandatory and considered unique: only one item per !column value will be created.',
            array('!column' => implode(', ', $uniques))),
          t('Fields <strong>!columns</strong> are mandatory and values in these columns are considered unique: only one entry per value in one of these columns will be created.',
            array('!columns' => implode(', ', $uniques)))),
      );
      $form['querypath']['sources']['help']['#markup'] = '<div class="help">' . theme('item_list', array('items' => $items)) . '</div>';
    }
    $variables = array();
    foreach ($mappings as $source => $target) {
      $form['querypath']['sources'][$source] = array(
        '#type' => 'textfield',
        '#title' => $target,
        '#description' => t('The CSS selector for this field.'),
        '#default_value' => isset($source_config['sources'][$source]) ? $source_config['sources'][$source] : '',
        '#maxlength' => 1024,
      );
      if (!empty($variables)) {
        $form['querypath']['sources'][$source]['#description'] .= '<br>' . t('The variables '. implode(', ', $variables). ' are availliable for replacement.');
      }
      $variables[] = '{' . $target . '}';
      $form['querypath']['attrs'][$source] = array(
        '#type' => 'textfield',
        '#title' => $target,
        '#description' => t('The attribute to return.'),
        '#default_value' => isset($source_config['attrs'][$source]) ? $source_config['attrs'][$source] : '',
        '#maxlength' => 1024,
      );
    }
    $form['querypath']['rawXML'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Select the queries you would like to return raw XML or HTML'),
      '#options' => $mappings,
      '#default_value' => isset($source_config['rawXML']) ? $source_config['rawXML'] : array(),
    );
    $form['querypath']['debug'] = array(
      '#type' => 'fieldset',
      '#title' => t('Debug'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['querypath']['debug']['options'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Debug query'),
      '#options' => array_merge(array('context' => 'context'), $mappings),
      '#default_value' => isset($source_config['debug']['options']) ? $source_config['debug']['options'] : array(),
    );
    return $form;
  }

  /**
   * Override parent::configForm().
   */
  public function configForm(&$form_state) {
    $form = $this->sourceForm($this->config);
    $form['querypath']['context']['#required'] = FALSE;
    $form['querypath']['#collapsed'] = FALSE;
    return $form;
  }

  /**
   * Override parent::sourceDefaults().
   */
  public function sourceDefaults() {
    return array();
  }

  /**
   * Define defaults.
   *
   * Override parent::configDefaults().
   */
  public function configDefaults() {
    return array(
      'context' => '',
      'sources' => array(),
      'debug' => array(),
      'attrs' => array(),
      'rawXML' => array(),
    );
  }

  /**
   * Override parent::sourceFormValidate().
   *
   * If the values of this source are the same as the base config we set them to
   * blank to that the values will be inherited from the importer defaults.
   *
   * @param &$values
   *   The values from the form to validate, passed by reference.
   */
  public function sourceFormValidate(&$values) {
    $values = $values['querypath'];
    asort($values);
    asort($this->config);
    if ($values === $this->config) {
      $values = array();
      return;
    }
    $this->configFormValidate($values);
  }

  /**
   * Override parent::sourceFormValidate().
   */
  public function configFormValidate(&$values) {
    $config = FALSE;
    $doc = '<html></html>';
    if (isset($values['querypath'])) {
      $values = $values['querypath'];
      $config = TRUE;
    }
    $values['context'] = trim($values['context']);
    try {
      qp($doc, $values['context']);
    }
    catch (CSSParseException $e) {
      $elem = 'feeds][FeedsQueryPathParser][querypath][context';
      if ($config) {
        $elem = 'querypath][context';
      }
      form_set_error($elem, $e->getMessage());
    }
    foreach ($values['sources'] as $key => &$source) {
      $source = trim($source);
      try {
        qp($doc, $source);
      }
      catch (CSSParseException $e) {
        $elem = 'feeds][FeedsQueryPathParser][querypath][sources][';
        if ($config) {
          $elem = 'querypath][sources][';
        }
        form_set_error($elem . $key, $e->getMessage());
      }
    }
  }

  /**
  * Override parent::getMappingSources().
  */
  public function getMappingSources() {
    $mappings = $this->filterMappings(feeds_importer($this->id)->processor->config['mappings']);
    $next = 0;
    if (!empty($mappings)) {
      $last_mapping = end(array_keys($mappings));
      $next = explode(':', $last_mapping);
      $next = $next[1] + 1;
    }
    return array(
      'querypathparser:' . $next => array(
        'name' => t('QueryPath Expression'),
        'description' => t('Allows you to configure a CSS selector expression that will populate this field.'),
      ),
    ) + parent::getMappingSources();
  }

  /**
   * Filters mappings, returning the ones that belong to us.
   */
  private function filterMappings($mappings) {
    $our_mappings = array();
    foreach ($mappings as $mapping) {
      if (strpos($mapping['source'], 'querypathparser:') === 0) {
        $our_mappings[$mapping['source']] = $mapping['target'];
      }
    }
    return $our_mappings;
  }

  /**
   * Prints out results from queries.
   */
  private function debug($item, $source) {
    if (in_array($source, $this->debug)) {
      $o = '<ul>';
      foreach ($item as $i) {
        if (is_object($i)) {
          $i = $i->html();
        }
        $o .= '<li>' . check_plain(var_export($i, TRUE)) . '</li>';
      }
      $o .= '</ul>';
      drupal_set_message($source . ':' . $o);
    }
  }
}
