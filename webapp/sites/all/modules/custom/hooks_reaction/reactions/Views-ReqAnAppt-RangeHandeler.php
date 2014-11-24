<?php

/**
 * Implements hook_views_query_alter().
 * Alters the view query to return only results within range of latitude/longitude.
 * Latitude, Longitude, and Range, will be pulled from the View's arguments.
 * This change will not be applied when argument are not given to the view
 */
hooks_reaction_add("views_query_alter",
	function (&$view, &$query) {
        
		// Only do this for the closes_resource_center_retheme View
		if ( $view->name === 'closes_resource_center_retheme' ) {
		
            // Only do this for the front_page_events Display in this View
            if ( $view->current_display === 'events' ) {
                
                // Only do this if all 2 arguments were past to this view
                if ( !empty($view->args) && !empty($view->args[0]) && !empty($view->args[1]) ) {
                    
                    $zip = $view->args[0];
                    $range = $view->args[1];
                    $locInfo = getLatLongFromZipCode($zip);
                    $lat = $locInfo['lat'];
                    $lng = $locInfo['lng'];
                    
                    ensureMySqlDistanceFunctionsExist();
                    $distLatLongFunct = "distLatLong({$lat}, {$lng}, field_data_field_event_latitude.field_event_latitude_value + 0.0, field_data_field_event_longitude.field_event_longitude_value + 0.0)";
                    $query->add_where_expression(0, $distLatLongFunct . " < $range");
                    
                }
            }
		}
        
		// Only do this for the closes_resource_center_retheme View
		if ( $view->name === 'closes_resource_center_retheme' ) {
		
            // Only do this for the front_page_resource_centers Display in this View
            if ( $view->current_display === 'resource_centers' ) {
                
                // Only do this if all 2 arguments were past to this view
                if ( !empty($view->args) && !empty($view->args[0]) && !empty($view->args[1]) ) {
                    
                    $zip = $view->args[0];
                    $range = $view->args[1];
                    $locInfo = getLatLongFromZipCode($zip);
                    $lat = $locInfo['lat'];
                    $lng = $locInfo['lng'];
                    
                    $range = $view->args[1];
                    
                    ensureMySqlDistanceFunctionsExist();
                    $distLatLongFunct = "distLatLong({$lat}, {$lng}, field_data_field_appoffice_lat.field_appoffice_lat_value + 0.0, field_data_field_appoffice_long.field_appoffice_long_value + 0.0)";
                    $query->add_where_expression(0, $distLatLongFunct . " < $range");
                    $query->orderby = array(
                        array(
                            'field' => "IF( field_data_field_appoffice_type.field_appoffice_type_value = 'SBA District Office', 0, 1 )",
                            'direction' => 'ASC'
                        ),
                        array(
                            'field' => $distLatLongFunct,
                            'direction' => 'ASC'
                        )
                    );
                    dsm($query);

                }
            }
		}
        
	}
);
