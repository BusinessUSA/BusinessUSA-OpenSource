<?php
/**
 * Administrative tools for the QueryPath library.
 *
 * @file
 */
  
/**
 * Implements hook_admin_form().
 */
function querypath_admin_form($context) {
  $form['qp'] = array(
    '#type' => 'fieldset',
    '#title' => t('Settings'),
    '#description' => t('The QueryPath library is not loaded by default because QueryPath itself is not included in the module. 
      First install QueryPath according to the directions in the README.txt file, then return to this page and enable QueryPath.'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['qp']['querypath_enabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('Load the QueryPath Library'),
    '#description' => t('Check this box to enable QueryPath. You should have installed the QueryPath library already.'),
    '#default_value' => variable_get('querypath_enabled', 0),
    '#return_value' => 1,
  );
  return drupal_settings($form);
}




