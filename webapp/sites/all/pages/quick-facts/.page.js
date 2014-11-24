(function($) {
  $(document).ready(function() {
    $("body.page-quick-facts #views-exposed-form-quick-facts-all-quick-facts").attr("action", "/quick-facts");
    $('body.page-quick-facts #edit-submit-quick-facts').addClass('btn-brand');

    var filters = $('body.page-quick-facts .view-quick-facts .view-filters'),
      content = $('body.page-quick-facts .view-quick-facts .view-content');
    content.insertBefore(filters);
    filters.addClass('col-md-4');
    content.addClass('col-md-8');
  });
})(jQuery);