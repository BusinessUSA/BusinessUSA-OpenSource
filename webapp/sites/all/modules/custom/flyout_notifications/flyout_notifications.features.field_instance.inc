<?php
/**
 * @file
 * flyout_notifications.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function flyout_notifications_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'node-recommended_flyout-field_date_published'
  $field_instances['node-recommended_flyout-field_date_published'] = array(
    'bundle' => 'recommended_flyout',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 4,
      ),
      'teaser' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_date_published',
    'label' => 'Date Published',
    'required' => 0,
    'settings' => array(
      'default_value' => 'now',
      'default_value2' => 'same',
      'default_value_code' => '',
      'default_value_code2' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'date',
      'settings' => array(
        'increment' => 15,
        'input_format' => 'm/d/Y - H:i:s',
        'input_format_custom' => '',
        'label_position' => 'above',
        'text_parts' => array(),
        'year_range' => '-0:+3',
      ),
      'type' => 'date_popup',
      'weight' => 36,
    ),
  );

  // Exported field_instance: 'node-recommended_flyout-field_display_page_path'
  $field_instances['node-recommended_flyout-field_display_page_path'] = array(
    'bundle' => 'recommended_flyout',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Path where the flyout you are creating will be displayed. Use a leading "/" and include anchors. i.e. "/find-opportunities#wizard-step-id-1" for the first step of the wizard or "/" for the homepage.',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 2,
      ),
      'teaser' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_display_page_path',
    'label' => 'Display Page Path',
    'required' => 1,
    'settings' => array(
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 34,
    ),
  );

  // Exported field_instance: 'node-recommended_flyout-field_image'
  $field_instances['node-recommended_flyout-field_image'] = array(
    'bundle' => 'recommended_flyout',
    'deleted' => 0,
    'description' => 'Add an image to display with your flyout. The optimal image dimensions are 70x70 pixels.',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'image',
        'settings' => array(
          'image_link' => '',
          'image_style' => '',
        ),
        'type' => 'image',
        'weight' => 1,
      ),
      'teaser' => array(
        'label' => 'above',
        'module' => 'image',
        'settings' => array(
          'image_link' => '',
          'image_style' => '',
        ),
        'type' => 'image',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_image',
    'label' => 'Image',
    'required' => 0,
    'settings' => array(
      'alt_field' => 0,
      'default_image' => 0,
      'file_directory' => '',
      'file_extensions' => 'png gif jpg jpeg',
      'max_filesize' => '',
      'max_resolution' => '70x70',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'image',
      'settings' => array(
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'throbber',
      ),
      'type' => 'image_image',
      'weight' => 33,
    ),
  );

  // Exported field_instance: 'node-recommended_flyout-field_landing_page_path'
  $field_instances['node-recommended_flyout-field_landing_page_path'] = array(
    'bundle' => 'recommended_flyout',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Path of the page the user will be navigated to when the flyout is clicked.
For Internal URLs: Use a leading "/" and include anchors. i.e. "/find-opportunities#wizard-step-id-1" for the first step of the wizard or "/" for the homepage.
For External URLs: Use the full path, including "http" or "https", depending on the URL. i.e. "http://business.usa.gov".',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 3,
      ),
      'teaser' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_landing_page_path',
    'label' => 'Landing Page Path',
    'required' => 1,
    'settings' => array(
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 35,
    ),
  );

  // Exported field_instance: 'node-recommended_flyout-field_snippet'
  $field_instances['node-recommended_flyout-field_snippet'] = array(
    'bundle' => 'recommended_flyout',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Add a short description of the flyout content.',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 0,
      ),
      'teaser' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_snippet',
    'label' => 'Snippet',
    'required' => 0,
    'settings' => array(
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 31,
    ),
  );

  // Exported field_instance: 'node-recommended_flyout-field_unpublishing_date'
  $field_instances['node-recommended_flyout-field_unpublishing_date'] = array(
    'bundle' => 'recommended_flyout',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 5,
      ),
      'teaser' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_unpublishing_date',
    'label' => 'Unpublishing Date',
    'required' => 0,
    'settings' => array(
      'default_value' => 'blank',
      'default_value2' => 'same',
      'default_value_code' => '',
      'default_value_code2' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'date',
      'settings' => array(
        'increment' => 15,
        'input_format' => 'm/d/Y - H:i:s',
        'input_format_custom' => '',
        'label_position' => 'above',
        'text_parts' => array(),
        'year_range' => '-0:+3',
      ),
      'type' => 'date_popup',
      'weight' => 37,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Add a short description of the flyout content.');
  t('Add an image to display with your flyout. The optimal image dimensions are 70x70 pixels.');
  t('Date Published');
  t('Display Page Path');
  t('Image');
  t('Landing Page Path');
  t('Path of the page the user will be navigated to when the flyout is clicked.
For Internal URLs: Use a leading "/" and include anchors. i.e. "/find-opportunities#wizard-step-id-1" for the first step of the wizard or "/" for the homepage.
For External URLs: Use the full path, including "http" or "https", depending on the URL. i.e. "http://business.usa.gov".');
  t('Path where the flyout you are creating will be displayed. Use a leading "/" and include anchors. i.e. "/find-opportunities#wizard-step-id-1" for the first step of the wizard or "/" for the homepage.');
  t('Snippet');
  t('Unpublishing Date');

  return $field_instances;
}
