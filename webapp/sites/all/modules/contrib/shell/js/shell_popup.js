
// This is loaded in the popup only.  The following is to ensure that
// if the popup is resized, the Shell gets resized too.
Drupal.behaviors.shellPopupStartup = {
attach: function (context, settings) {

  // Begin by setting the history hight to whatever the popup window's
  // height is.
  var newWindowHeight = $(window).height();  
  $("#shell-screen-history").css("min-height", newWindowHeight - 70 );  
  
  //If the user resizes the window, adjust the history height
  $(window).bind("resize", resizeWindow);
  function resizeWindow( e ) {  
    var newWindowHeight = $(window).height();  
    $("#shell-screen-history").css("min-height", newWindowHeight - 70 );  
  }
  
}
}