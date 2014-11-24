  drawnMarkers = [];

jQuery(document).ready( function () {
    jQuery('.resourcecenters-filter-container').bind('click', function () {
        jQuery('.resourcecenters-filter:visible').each( function () {
        
            var jqThisContainer = jQuery(this);
            var thisFilterText = jqThisContainer.find('label:visible').text();
            var thisFilterChecked = jqThisContainer.find('input').get(0).checked

            var thisFilterAssocRealFilter = jQuery();
            var thisFilterAssocAllRealFilters = jQuery('#edit-field-appoffice-type-value option');

            for ( var x = 0 ; x < thisFilterAssocAllRealFilters.length ; x++ ) {
                //consoleLog(thisFilterAssocAllRealFilters.eq(x).attr('value') + " == " + thisFilterText);
                if ( thisFilterAssocAllRealFilters.eq(x).text() == thisFilterText ) {
                    thisFilterAssocRealFilter = thisFilterAssocAllRealFilters.eq(x);
                    break;
                }
            }

            if ( thisFilterAssocRealFilter.length > 0 ) {
                thisFilterAssocRealFilter.get(0).checked = thisFilterChecked;
                if ( thisFilterChecked ) {
                    thisFilterAssocRealFilter.attr('selected', 'selected');
                } else {
                    thisFilterAssocRealFilter.removeAttr('selected');
                }
            }

        });

      if (jQuery('[name="field_appoffice_type_value[]"]').val().length == 1) {
        jQuery('[name="field_appoffice_type_value[]"] option[value!="U.S. Commercial Service Office"]').attr('selected', true);
        consoleLog('More than one filter must be selected. Filters now at: ' + $('[name="field_appoffice_type_value[]"]').val().length);
      }
    });
    
});
  
    function removeDrawnMarkers() {
        while (drawnMarkers.length > 0) {
          drawnMarkers[0].setMap(null);
          drawnMarkers.shift();
        }
    }

    function drawMarkersFromView() {
        // Clear map so we can update.
        removeDrawnMarkers();

        jQuery('.views-row').each( function () {

          var jqThis = jQuery(this);
          var pinIcon = jqThis.find('.pin-image img').attr('src');
          var title = jqThis.find('.views-field-title .field-content').text();
          var lat = jqThis.find('.googlemap-markme-lat').text();
          var lng = jqThis.find('.googlemap-markme-lng').text();
          var marker_address = jqThis.find('.marker-address').text();
          // if (drawnMarkers.length > 3) return;

          if (jQuery.isNumeric(lat) && jQuery.isNumeric(lng)) {
            var marker_postion = new google.maps.LatLng(lat, lng);
            var marker = new google.maps.Marker({
              position: marker_postion,
              map: map,
              title: title,
              icon: pinIcon
            });
            var infowindow = new google.maps.InfoWindow({
              content: " "
            });

            google.maps.event.addListener(marker, 'click', function() {
              infowindow.setContent("<div class='marker-address'>" + marker_address + "</div>");
              infowindow.open(map, this);
            });
            drawnMarkers.push(marker);
          }

        });
    }