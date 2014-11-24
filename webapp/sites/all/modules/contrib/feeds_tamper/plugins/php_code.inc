<?php

/**
 * @file
 * Execute PHP Code.
 */

$plugin = array(
  'form' => 'feeds_tamper_php_code_form',
  'callback' => 'feeds_tamper_php_code_callback',
  'name' => 'PHP Code',
  'multi' => 'skip',
  'category' => 'Other',
);

function feeds_tamper_php_code_form($importer, $element_key, $settings) {
  $form = array();
  $mappings = $importer->processor->config['mappings'];

  $replace = array();
  foreach ($mappings as $mapping) {
    $replace[] = '[' . $mapping['source'] . ']';
  }

  $form['text'] = array(
    '#type' => 'textarea',
    '#title' => t('Replacement pattern'),
    '#default_value' => isset($settings['text']) ? $settings['text'] : '',
    '#description' => t('Execute any PHP code. Be sure to include the &lt;?php and ?&gt; tags.'),
  );
  $form['help'] = array(
    '#type' => 'fieldset',
    '#title' => t('Available Replacement Patterns'),
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,
    '#value' => theme('item_list', array('items' => $replace)),
  );
  return $form;
}

function feeds_tamper_php_code_callback($result, $item_key, $element_key, &$field, $settings) {
  $trans = array();
  
  $item = $result->items[$item_key];
  foreach ($item as $key => $value) {
    $trans['[' . $key . ']'] = $value;
  }

  $field = php_eval(strtr($settings['text'], $trans));

}

