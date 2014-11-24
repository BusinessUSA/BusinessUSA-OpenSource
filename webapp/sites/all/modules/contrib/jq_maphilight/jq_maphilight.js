(function ($) {
  Drupal.behaviors.jq_maphilight = {
    attach: function(context, settings) {
      var options = {
        fill: (settings.jq_maphilight.fill == 'true' ? true : false),
        fillColor: settings.jq_maphilight.fillColor,
        fillOpacity: settings.jq_maphilight.fillOpacity,
        stroke: (settings.jq_maphilight.stroke == 'true' ? true : false),
        strokeColor: settings.jq_maphilight.strokeColor,
        strokeOpacity: settings.jq_maphilight.strokeOpacity,
        strokeWidth: settings.jq_maphilight.strokeWidth,
        fade: (settings.jq_maphilight.fade == 'true' ? true : false),
        alwaysOn: (settings.jq_maphilight.alwaysOn == 'true' ? true : false),
        neverOn: (settings.jq_maphilight.neverOn == 'true' ? true : false),
        groupBy: (settings.jq_maphilight.groupBy == 'true' ? true : false)
      }
  if (settings.jq_maphilight.allMapsEnabled  == 'true') {
    $('img[usemap]').maphilight(options);
  }
  else {
    $('.jq_maphilight').maphilight(options);
  }
}}})(jQuery);