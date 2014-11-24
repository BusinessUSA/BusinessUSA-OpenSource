(function($) {
  $(document).ready(function() {
    // Change page title on events-search page.
    $('body.page-events-search #page-title').text('Events');

    // Fix download link on the export portal gov .store page.
    setTimeout(function() {
      $('.page-export-renewable-energy-energy-efficiency-re-ee--webinars-seminars-for-renewable-energy-and-energy-efficiency-exporters .centerallcontent > div .List-1 a[href="/export-portal?static/PW7642864_Latest_eg_main_036295.wmv"]').attr('href', '/sites/default/files/export-gov-content/static/PW7642864_Latest_eg_main_036295.wmv.store');
    }, (1000));
  });
})(jQuery);
