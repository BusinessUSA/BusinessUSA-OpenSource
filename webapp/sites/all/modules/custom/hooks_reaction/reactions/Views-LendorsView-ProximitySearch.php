<?php

/**
 * Implements hook_views_query_alter().
 * Alters the view query to return only results within 25 miles range of latitude/longitude.
 * Latitude, Longitude, will be pulled from the View's arguments.
 * This change will not be applied when arguments are not given to the view
 */
hooks_reaction_add("views_query_alter",
	function (&$view, &$query) {
		
		// Only do this for the vendors_near_you View
		if ( $view->name === 'vendors_near_you' && ($view->current_display === 'block_1' || $view->current_display === 'block_5')) {
    
            // Only do this if atleast 2 arguments were past to this view
            if ( !empty($view->args) && !empty($view->args[0]) && !empty($view->args[1]) ) {
                $lat = $view->args[0];
                $ln = $view->args[1];
                
                $query->add_field('field_data_field_vend_address_zip_lat', 'field_vend_address_zip_lat_value');
                $query->add_field('field_data_field_vend_address_zip_lng', 'field_vend_address_zip_lng_value');
                
                $distLatLongFunct = "distLatLong($lat, $ln, (
                            SELECT lat.field_vend_address_zip_lat_value
                            FROM field_data_field_vend_address_zip_lat lat
                            WHERE lat.entity_id = node.nid
                            LIMIT 1
                        ), 
                        (
                            SELECT lng.field_vend_address_zip_lng_value
                            FROM field_data_field_vend_address_zip_lng lng
                            WHERE lng.entity_id = node.nid
                            LIMIT 1
                        )
                    )
                ";
                
                $query->add_where_expression(0, $distLatLongFunct . " < 100");
                
                $query->orderby = array(
                    array(
                        'field' => $distLatLongFunct,
                        'direction' => 'ASC'
                    )
                );
                
                //$query->add_where_expression(0, 'field_vend_address_zip_value = 90210');

            }
			
			if ( strpos(request_uri(), '-DEBUG-VIEWSQUERYALTER-VENDORSNEARYOU-VERBOSE-') !== false ) {
                dsm(
                    array(
                        'view' => $view,
                        'view-args' => $view->args,
                        'query' => $query
                    )
                );
			}
		}
		
		// Only do this for the vendors_near_you View
		if ( $view->name === 'vendors_near_you' && ($view->current_display === 'block_2' || $view->current_display === 'block_3')) {
    
            // Only do this if atleast 2 arguments were past to this view
            if ( !empty($view->args) && !empty($view->args[0]) && !empty($view->args[1]) ) {
                $lat = $view->args[0];
                $ln = $view->args[1];
                
                $query->add_field('field_data_field_appoffice_lat', 'field_appoffice_lat_value');
                $query->add_field('field_data_field_appoffice_long', 'field_appoffice_long_value');
                
                $distLatLongFunct = "distLatLong($lat, $ln, (
                            SELECT lat.field_appoffice_lat_value
                            FROM field_data_field_appoffice_lat lat
                            WHERE lat.entity_id = node.nid
                            LIMIT 1
                        ), 
                        (
                            SELECT lng.field_appoffice_long_value
                            FROM field_data_field_appoffice_long lng
                            WHERE lng.entity_id = node.nid
                            LIMIT 1
                        )
                    )
                ";
                
                $query->add_where_expression(0, $distLatLongFunct . " < 100");
                $query->orderby = array(
                    array(
                        'field' => $distLatLongFunct,
                        'direction' => 'ASC'
                    )
                );
            }
		}
	}
);