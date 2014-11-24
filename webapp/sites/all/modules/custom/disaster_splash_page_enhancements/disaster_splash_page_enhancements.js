(function($) {
  // Determine whether or not this is the target page and
  // hide the enhancements if this is not the splash page.
  $(document).ready(function() {
    if ((window.location.hash && window.location.href.split("#").length != 1)) {
      $('.wizard-outside-sidebars-container').hide();
    }
  });

  // Hide the enhancements if the "Get Started" button has been pressed.
  $(document).on('click', '.wizard-uniqueid-disaster-assistance .question-controle-for-wiztag-splash-next-button input[value="Get Started"]', function(e) {
    $('.wizard-outside-sidebars-container').hide();
  });

  // Show the enhancements if the back button has been pressed and it leads
  // to the splash page.
  $(document).on('click', '.wizard-uniqueid-disaster-assistance .wizard-back', function(e) {
    setTimeout(function() {
      if (!window.location.hash || window.location.href.split("#").length == 1) {
        $('.wizard-outside-sidebars-container').show();
      }
    }, 10);
  });

  // Show the enhancements if the home button has been pressed.
  $(document).on('click', '.wizard-uniqueid-disaster-assistance .wizard-title a', function(e) {
    setTimeout(function() {
      if (!window.location.hash || window.location.href.split("#").length == 1) {
        $('.wizard-outside-sidebars-container').show();
      }
    }, 10);
  });
})(jQuery);