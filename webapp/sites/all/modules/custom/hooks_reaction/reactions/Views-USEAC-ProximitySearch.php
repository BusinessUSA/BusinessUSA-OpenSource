<?php

/**
 * Implements hook_views_query_alter().
 * Alters the view query to return only results within range of latitude/longitude.
 * Latitude, Longitude, and Range, will be pulled from the View's arguments.
 * This change will not be applied when argument are not given to the view
 */
hooks_reaction_add("views_query_alter",
	function (&$view, &$query) {
        
        // Debug verbosity
        if ( strpos(request_uri(), '-DEBUG-VIEWSQUERYALTER-USEAC-VERBOSE-') !== false ) {
            print '<div class="debug-info debug-viewqueryalter-info" style="display: none;">';
                print '<!--';
                    var_dump($query);
                print '-->';
            print '</div>';
        }
    
		// Only do this for the useac_location_exporting_wizards View
		if ( $view->name === 'useac_location_exporting_wizards' ) {
		
            // Only do this if all 3 arguments were past to this view
            if ( !empty($view->args) && !empty($view->args[0]) && !empty($view->args[1]) && !empty($view->args[2]) ) {
            
                list( $lat, $ln, $range ) = $view->args;
                
                ensureMySqlDistanceFunctionsExist();
                $distLatLongFunct = "distLatLong($lat, $ln, field_data_field_appoffice_lat.field_appoffice_lat_value + 0.0, field_data_field_appoffice_long.field_appoffice_long_value + 0.0)";
                $query->add_where_expression(0, $distLatLongFunct . " < $range");
                
                $query->add_field('field_data_field_appoffice_lat', 'field_appoffice_lat_value');
                $query->add_field('field_data_field_appoffice_long', 'field_appoffice_long_value');
                
                $query->fields['node_title'] = array(
                            'field' => "CONCAT( node.title, ' ', convert($distLatLongFunct, DECIMAL) ) ",
                            'table' => '',
                            'alias' => 'node_title'
                );
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
