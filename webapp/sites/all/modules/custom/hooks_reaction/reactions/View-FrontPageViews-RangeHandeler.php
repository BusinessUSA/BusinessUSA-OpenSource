<?php

/**
 * Implements hook_views_query_alter().
 * Alters the view query to return only results within range of latitude/longitude.
 * Latitude, Longitude, and Range, will be pulled from the View's arguments.
 * This change will not be applied when argument are not given to the view
 */
hooks_reaction_add("views_query_alter",
	function (&$view, &$query) {

		// Only do this for the front_page_resource_and_events_views View
		if ( $view->name === 'front_page_resource_and_events_views') {

            // Only do this for the front_page_events Display in this View
            if ( $view->current_display === 'front_page_events') {

                // Only do this if all 2 arguments were past to this view
                if ( !empty($view->args) && !empty($view->args[0]) && !empty($view->args[1]) ) {
                    list( $lat, $ln ) = $view->args;
                    $range = 75;

                    ensureMySqlDistanceFunctionsExist();
                    $distLatLongFunct = "distLatLong($lat, $ln, field_data_field_event_latitude.field_event_latitude_value + 0.0, field_data_field_event_longitude.field_event_longitude_value + 0.0)";
                    $query->add_where_expression(0, $distLatLongFunct . " < $range");

                }
            }
		}

		// Only do this for the front_page_resource_and_events_views View
		if ( $view->name === 'front_page_resource_and_events_views' ) {

            // Only do this for the front_page_resource_centers Display in this View
            if ( $view->current_display === 'front_page_resource_centers' ) {

                // Only do this if all 2 arguments were past to this view
                if ( !empty($view->args) && !empty($view->args[0]) && !empty($view->args[1]) ) {

                    list( $lat, $ln ) = $view->args;
                    $range = 500;

                    ensureMySqlDistanceFunctionsExist();
                    $distLatLongFunct = "distLatLong($lat, $ln, field_data_field_appoffice_lat.field_appoffice_lat_value + 0.0, field_data_field_appoffice_long.field_appoffice_long_value + 0.0)";
                    $query->orderby = array(
                        array(
                            'field' => $distLatLongFunct,
                            'direction' => 'ASC'
                        )
                    );

                }
            }
		}

    // Only do this for the business_sunday_events View
    if ( $view->name === 'business_sunday_events' ) {

      // Only do this for the front_page_resource_centers Display in this View
      if ( $view->current_display === 'business_sunday' ) {

        // Only do this if all 2 arguments were past to this view
        if ( !empty($view->args) && !empty($view->args[0]) && !empty($view->args[1]) ) {

          list( $lat, $ln ) = $view->args;

          ensureMySqlDistanceFunctionsExist();
          $distLatLongFunct = "distLatLong($lat, $ln, field_data_field_event_latitude.field_event_latitude_value + 0.0, field_data_field_event_longitude.field_event_longitude_value + 0.0)";
          $query->orderby = array(
            array(
              'field' => $distLatLongFunct,
              'direction' => 'ASC'
            )
          );

        }
      }
    }

    // Apply pager functionality to API views.
    if (in_array($view->name, array('article_api', 'data_api', 'event_api', 'event_api', 'keyword_search_api', 'program_api', 'service_api', 'success_story_api', 'tool_api'))) {
      if (!empty($_GET['page']) && is_numeric($_GET['page'])) {
        $pager = $view->display_handler->get_plugin('pager');
        // We have to do this because these views do use views_plugin_pager_some which doesn't actually support pages.
        $offset = $pager->options['items_per_page'] * ($_GET['page'] - 1);
        $view->set_offset($offset);
      }
    }
	}
);

hooks_reaction_add("views_pre_build",
	function (&$view) {
    if (in_array($view->name, array('article_api', 'data_api', 'event_api', 'event_api', 'keyword_search_api', 'program_api', 'service_api', 'success_story_api', 'tool_api'))) {
      $view->init_style();
      $page = !isset($_GET['page']) ? 1 : $_GET['page'];
      $view->style_plugin->options['filename'] = preg_replace('/(.*)(\.[^.]*)/', '\1_page_' . intval($page) . '\2', $view->style_plugin->options['filename']);
    }
  }
);
