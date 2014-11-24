<?php
  $zip = strip_tags(trim($_GET['zip']));
  if(!empty($zip)) {
    $zip = strip_tags(trim($_GET['zip']));
    $locInfo = getLatLongFromZipCode($zip);
  } else {
    $zip = 20500;
    $locInfo = getLatLongFromZipCode($zip);
  }
  print views_embed_view('vendors_near_you', 'block_1', $locInfo['lat'], $locInfo['lng']);
?>
  <script>
    (function($) {
      $('.view-vendors-near-you').addClass('col-md-12');
      $(document).ready(function(){
        // Add panel collapse arrow
        $('.panel-collapse.collapse').prev().css( "background", "url('/sites/all/themes/bizusa/images/icons/block-collapsed.png') no-repeat 92% center #e2e2e2");
        $('.panel-collapse.collapse.in').prev().css( "background", "url('/sites/all/themes/bizusa/images/icons/block-expanded.png') no-repeat 92% center #e2e2e2");


        $('.panel-collapse').on('hidden.bs.collapse', function (e) {
          var panelID = String($(e.target).attr('id'));
          $('#'+panelID).prev().css( "background", "url('/sites/all/themes/bizusa/images/icons/block-collapsed.png') no-repeat 92% center #e2e2e2");
        });

        $('.panel-collapse').on('shown.bs.collapse', function (e) {
          var panelID = String($(e.target).attr('id'));
          $('#'+panelID).prev().css( "background", "url('/sites/all/themes/bizusa/images/icons/block-expanded.png') no-repeat 92% center #e2e2e2");
        });

      });
    })(jQuery);
  </script>