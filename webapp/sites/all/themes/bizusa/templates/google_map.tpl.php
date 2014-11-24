<!-- rendersource - This is coming from sites/all/themes/bizusa/templates/google_map.tpl.php -->
<?php
drupal_add_js('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', 'external');
$height = $height ? : '350px';
$width = $width ? : '100%';
?>
<style>
  html body .map-canvas {
    height: <?php echo $height; ?>;
    width: <?php echo $width; ?>;
    margin: 0px;
    padding: 0px;
    border: 1px solid #718da8;
  <?php if($hideMap) {print "display: none;";
} ?>
  }
</style>
<a class="viewhide-map" href="#"><?php if ($hideMap) {
    print "View Map";
  }
  else {
    print "Hide Map";
  } ?></a>
<div class="map-canvas" latitude="<?php print $targetLatitude; ?>"
     longitude="<?php print $targetLongitude; ?>"></div>
<script>
  function getLatLong(address) {
    var geo = new google.maps.Geocoder;
    geo.geocode({'address': address}, function (results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        results[0].geometry.location;
        var latitude = results[0].geometry.location.lat();
        var longitude = results[0].geometry.location.lng();
        map_canvas = jQuery('.map-canvas');
        map_canvas.attr('latitude', latitude).attr('longitude', longitude);
        initialize(map_canvas[0], latitude, longitude);
        return results[0].geometry.location;
      }
      return false;
    });
  }
  //If we're showing just one map, get the variables from the server

  <?php if(!$hideMap): ?>
      var targetLatitude = <?php print $targetLatitude; ?>;
      var targetLongitude = <?php print $targetLongitude; ?>;
      var targetCityState = "<?php print $cityState; ?>";

      if (targetLatitude == 0 && targetLongitude == 0 && targetCityState.length > 0) {
        getLatLong(targetCityState);
      }
      google.maps.event.addDomListener(window, 'load', initialize(jQuery('.map-canvas')[0], targetLatitude, targetLongitude));
  <?php endif; ?>
  //this only needs to be included once, so don't include if the count is greater than 0
  <?php if((int) $count == 0){ ?>
  function initialize(map_canvas, latitude, longitude) {
    var mapLatLng = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
    var mapOptions = {
      zoom: 12,
      center: mapLatLng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(map_canvas, mapOptions);
    var marker = new google.maps.Marker({
      position: mapLatLng,
      map: map,
      title: '<?php echo addslashes($title); ?>'
    });
  }
  jQuery(document).ready(function () {
    jQuery(".viewhide-map").click(function (event) {
      event.preventDefault();
      var show_map_link = jQuery(this),
        map_canvas = show_map_link.next().toggle(),
        latitude = map_canvas.attr('latitude'),
        longitude = map_canvas.attr('longitude');
        show_map_link.text(map_canvas.is(':visible') ? "Hide Map" : "View Map"),
        initialize(map_canvas[0], latitude, longitude);
    });
  });
  <?php } ?>
</script>

