<?php

/**
 * Here we override the default HTML output of Drupal.
 */
 
function bizusa_theme($existing, $type, $theme, $path) {
    $themes = array();
    $themes['sharewidget'] = array(
        'template' => 'sharewidget',
        'path' => 'sites/all/themes/bizusa/templates/',
        'variables' => array(
            'message' => 'test message',
            'emailMessage' => 'email message',
            'expandable' => true,
            'addClasses' => '',
            'showCounters' => true,
            'emailShareBody' => 
                '
                    Hello,

                    You are receiving this email because the sender opted to share the resource(s) found in BusinessUSA with you.

                    Please use the following URL to access the shared link: -URL-HERE-

                    If you need more information or help, contact us at help.business.usa.gov

                    Thank You
                    - BusinessUSA Team

                    [This is a system generated message]
                ',
            'emailShareSubject' => 'Shared Link from BusinessUSA'
        ),
        'type' => 'theme'
    );
    $themes['search_results_related_wizards'] = array(
        'template' => 'search_results_related_wizards',
        'path' => 'sites/all/themes/bizusa/templates/',
        'variables' => array(
            'search' => '*'
        ),
        'type' => 'theme'
    );
    $themes['search_results_search_across_business_agencies'] = array(
        'template' => 'search_results_search_across_business_agencies',
        'path' => 'sites/all/themes/bizusa/templates/',
        'variables' => array(
            'search' => '*'
        ),
        'type' => 'theme'
    );
    $themes['legacy_swimlane_page'] = array(
        'template' => 'legacy_swimlane_page',
        'path' => 'sites/all/themes/bizusa/templates/',
        'variables' => array(),
        'type' => 'theme'
    );
    $themes['google_map'] = array(
        'template' => 'google_map',
        'path' => 'sites/all/themes/bizusa/templates/',
        'variables' => array(
            'initialize' => 'immediately',
            'targetLatitude' => 0,
            'targetLongitude' => 0,
            'zoom' => 5,
        ),
        'type' => 'theme'
    );
    return $themes;
}

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('clear_registry')) {
  // Rebuild .info data.
  system_rebuild_theme_data();
  // Rebuild theme registry.
  drupal_theme_rebuild();
}

function bizusa_preprocess_html(&$vars, $hook) {

  /* If there is a unempty global $overrideBodyMarkup variable defined, then 
      the markup within the body tag will be overridden. We shall state this in 
      a classname associated with the body tag
  */
  if ( !empty($GLOBALS['overrideBodyMarkup']) ) {
    $vars['classes_array'][] = 'body-markup-overridden';
  } else {
    $vars['classes_array'][] = 'body-markup-drupal-rendered';
  }
  
  // Adding classes wether #navigation is here or not
  if (!empty($vars['main_menu']) or !empty($vars['sub_menu'])) {
    $vars['classes_array'][] = 'with-nav';
  }
  if (!empty($vars['secondary_menu'])) {
    $vars['classes_array'][] = 'with-subnav';
  }
  
  // Add a class name to the body that is related to the URL path to this page
    $classBasedOnPath = $_SERVER['REQUEST_URI'];
    $queryPos = strpos($classBasedOnPath, '?');
    if ( $queryPos !== false ) {
        $classBasedOnPath = substr($classBasedOnPath, 0, $queryPos);
    }
    $classBasedOnPath = str_replace('/', '-', $classBasedOnPath);
    $classBasedOnPath = str_replace('_', '-', $classBasedOnPath);
    $classBasedOnPath = str_replace(' ', '-', $classBasedOnPath);
    $classBasedOnPath = str_replace('.', '-', $classBasedOnPath);
    $classBasedOnPath = strtolower($classBasedOnPath);
    $classBasedOnPath = 'pagepath-' . trim($classBasedOnPath, '-');
    $classBasedOnPath = str_replace('--', '-', $classBasedOnPath);
    $vars['classes_array'][] = $classBasedOnPath;
  
  // If this is an unauthenticated user, or if this is an authenticated user, but not the admin, add a not-administrator class to the body
    global $user;
    if ( empty($user) || empty($user->roles) || in_array('administrator', $user->roles) === false ) {
        $vars['classes_array'][] = 'not-administrator';
    }
  
  // Each roll of the authenticated user shall be added as a classname onto the body tag
    if ( !empty($user) || !empty($user->roles) ) {
        foreach ( $user->roles as $role ) {
            $vars['classes_array'][] = 'role-' . strtolower(str_replace(' ', '-', $role));
        }
    }

    if ( $vars['is_front'] === true ) {
        drupal_add_css('/usr-dashboard/frontpage/find-and-print-compiled-less', array('type' => 'external'));
        drupal_add_js('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array('type' => 'external'));
    }
    
    //Add 'description' & 'keywords' meta-tags in the header
    $html_head = array(
        'front_page_description' => array(
            '#tag' => 'meta',
            '#attributes' => array(
                'name' => 'description',
                'content' => 'BusinessUSA implements a "no wrong door" approach for small businesses and exporters by connecting businesses to the services and information relevant to them.',
            ),
        ),
        'front_page_keywords' => array(
            '#tag' => 'meta',
            '#attributes' => array(
                'name' => 'keywords',
                'content' => 'business, usa, grant, market, logistics, forecast, wizard, guide, small, loans, women, LLC, EIN, plan, license, owned, minority, Veteran, compliance, profit, DBA, import, export, taxes, data, debt,finance, permit, invention, patent, insurance, employee, self-employed, cash',
            ),
        ),
    );
    //Add meta-tags only in the front-page
    foreach ($html_head as $key => $data) {
        if(request_path() == NULL || request_uri() === "/") {
            drupal_add_html_head($data, $key);
        }
    }
}

function bizusa_preprocess_block(&$vars, $hook) {
  // Add a striping class.
  $vars['classes_array'][] = 'block-' . $vars['block_zebra'];

  // Add first/last block classes
  $first_last = "";
  // If block id (count) is 1, it's first in region.
  if ($vars['block_id'] == '1') {
    $first_last = "first";
    $vars['classes_array'][] = $first_last;
  }
  // Count amount of blocks about to be rendered in that region.
  $block_count = count(block_list($vars['elements']['#block']->region));
  if ($vars['block_id'] == $block_count) {
    $first_last = "last";
    $vars['classes_array'][] = $first_last;
  }
}

function bizusa_preprocess_node(&$vars) {
  // Placeholder. Add your node preprocessing code here.

}


/**
 * Converts a string to a suitable html ID attribute.
 *
 * http://www.w3.org/TR/html4/struct/global.html#h-7.5.2 specifies what makes a
 * valid ID attribute in HTML. This function:
 *
 * - Ensure an ID starts with an alpha character by optionally adding an 'n'.
 * - Replaces any character except A-Z, numbers, and underscores with dashes.
 * - Converts entire string to lowercase.
 *
 * @param $string
 * 	The string
 * @return
 * 	The converted string
 */	
function bizusa_id_safe($string) {
  // Replace with dashes anything that isn't A-Z, numbers, dashes, or underscores.
  $string = strtolower(preg_replace('/[^a-zA-Z0-9_-]+/', '-', $string));
  // If the first character is not a-z, add 'n' in front.
  if (!ctype_lower($string{0})) { // Don't use ctype_alpha since its locale aware.
    $string = 'id'. $string;
  }
  return $string;
}

/**
 * Generate the HTML output for a menu link and submenu.
 *
 * @param $variables
 *  An associative array containing:
 *   - element: Structured array data for a menu link.
 *
 * @return
 *  A themed HTML string.
 *
 * @ingroup themeable
 * 
 */
function bizusa_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  // Adding a class depending on the TITLE of the link (not constant)
  $element['#attributes']['class'][] = bizusa_id_safe($element['#title']);
  // Adding a class depending on the ID of the link (constant)
  $element['#attributes']['class'][] = 'mid-' . $element['#original_link']['mlid'];
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

