<?php

/**
 * @file
 * Contains the administration pages for LESS.
 *
 */

function less_settings_form($form, &$form_state) {

  $form['less_flush'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#value' => 'Click this button regenerate all LESS files once.',
  );

  $form['less_flush']['flush'] = array(
    '#type' => 'submit',
    '#submit' => array('_flush_less'),
    '#value' => 'Flush LESS files',
  );

  $form['less_devel'] = array(
    '#type' => 'checkbox',
    '#title' => t('LESS developer mode'),
    '#description' => t('Enable developer mode to ensure LESS files are regenerated every page load.'),
    '#default_value' => variable_get('less_devel', FALSE),
  );
  
  $form['less_watch'] = array(
    '#type' => 'checkbox',
    '#title' => t('LESS watch mode'),
    '#description' => t('Enable watch mode while developer mode is active to automatically reload styles when changes are detected, including changes to @import-ed files. Does not cause a page reload.'),
    '#default_value' => variable_get('less_watch', TRUE),
    '#states' => array(
      'disabled' => array(
        ':input[name="less_devel"]' => array('unchecked' => TRUE),
      ),
    ),
  );
  
  $form['#submit'] = array('less_settings_form_submit');
  
  return system_settings_form($form);
}

function less_settings_form_submit($form, &$form_state) {
  cache_clear_all('less:', 'cache', TRUE);
}

function _flush_less($form, &$form_state) {

  _less_get_dir(TRUE);
  cache_clear_all('less:', 'cache', TRUE);

  drupal_set_message(t('LESS files cache cleared.'), 'status');
}
