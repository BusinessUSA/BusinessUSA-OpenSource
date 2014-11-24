<?php

/**
 * @file
 *   Admin include for Modernizr module.
 */

/**
 * Generates new Modernizr URL for admin interface
 * Callback for 'admin/config/development/modernizr'.
 */
function modernizr_generate_url() {
  // Reset our lists of needed Modernizr tests in drupal_static.
  drupal_static_reset('modernizr_api_list');

  // Get latest tests from modules and themes
  $tests = modernizr_api_list();

  // Begin assembling link to re-download Modernizr
  $link = 'http://www.modernizr.com/download/#-';

  // Pull tests that are currently set.
  $current_tests = _modernizr_current_build();

  if (is_null($current_tests)) {
    // We have no build of modernizr.
    drupal_set_message(t("You don't seem to have a custom build of Modernizr installed yet. This page will help generate one for you."), 'warning');
  }
  else {
    foreach ($current_tests as $current_test) {
      // Adds all current tests to the array of tests.
      if (!isset($tests[$current_test])) {
        $tests[$current_test] = array(
          'name' => $current_test,
          'source' => array(modernizr_get_filename()),
          'desc' => _modernizr_get_desc($current_test),
          'docs' => '',
          'caniuse' => '',
        );
      }
    }
  }

  // Create indexes for first download link/desc to place them
  // above everything. They will be populated later.
  $elements['download_modernizr1']['link'] = array();
  $elements['download_modernizr1']['description'] = array();

  // Create the tests heading render element.
  $elements['tests']['heading-tests'] = array(
    '#theme' => 'html_tag',
    '#value' => t('Current Modernizr Tests'),
    '#tag' => 'h2',
  );

  // Create the tests description render element.
  $elements['tests']['description'] = array(
    '#theme' => 'html_tag',
    '#value' => t('Currently enabled Drupal modules and themes have requested the following Modernizr tests:'),
    '#tag' => 'p',
  );

  // Create a container to indent everything
  $elements['tests']['list'] = array(
    '#prefix' => '<div class="modernizr-tests">',
    '#suffix' => '</div>',
  );

  // Check to see if there are any registered tests.
  if (!empty($tests)) {
    // Loop through each registered test.
    foreach ($tests as $key => $test) {
      // API was changed between 3.0 and 3.1. We originally specified `module` but
      // since themes can also specify tests the attribute was changed to `source`
      $source = (isset($test['source'])) ? $test['source'] : $test['module'];

      // Check to see if this test has been set. If not, add it.
      if (!isset($elements['tests']['list'][$key])) {
        $elements['tests']['list'][$key]['name'] = array(
          '#theme' => 'html_tag',
          '#value' => $key,
          '#tag' => 'h3',
        );
      }

      // Create the description for this test.
      $elements['tests']['list'][$key]['tests'][] = array(
        'description' => array(
          '#markup' => _modernizr_get_desc($key),
          '#prefix' => '<p class="desc">',
          '#suffix' => '</p>',
        ),
      );

      // @TODO: Check to see if this test has already been added by another module.
      $link .= $key . '-';
    }
  }
  else {
    $elements['tests']['list']['#markup'] = '<p>There are no Modernizr tests registered</p>';
  }

  // Create the download link render element.
  $download_link = array(
    '#prefix' => '<div class="download-modernizr">',
    '#theme' => 'link',
    '#text' => t('Download your Modernizr production build'),
    '#path' => substr($link, 0, -1), // Truncate the last hyphen.
    '#options' => array(
      'attributes' => array(
        'class' => array(
          'button',
        ),
        'target' => '_blank',
      ),
      'html' => FALSE,
    ),
    '#suffix' => '</div>',
  );

  // Create the download description render element.
  $download_desc = array(
    '#theme' => 'html_tag',
    '#value' => t('The button links to a custom Modernizr build based on the tests listed above. <br/> Once you download the script, place it inside <b>!path</b> and !cc.', array(
      '!path' => module_exists('libraries') ? libraries_get_path('modernizr') : 'sites/all/libraries/modernizr',
      '!cc' => l(t('clear your cache'), 'admin/config/development/performance'),
    )),
    '#tag' => 'p',
  );

  // Print the Download link above and below the tests.
  $elements['download_modernizr1']['link'] = $download_link;
  $elements['download_modernizr2']['link'] = $download_link;

  // Print the Download description above and below the tests.
  $elements['download_modernizr1']['description'] = $download_desc;
  $elements['download_modernizr2']['description'] = $download_desc;

  // Load admin CSS
  drupal_add_css(drupal_get_path('module', 'modernizr') . '/css/modernizr.admin.css');

  return $elements;
}
