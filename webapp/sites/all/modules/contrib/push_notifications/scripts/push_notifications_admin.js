(function ($) {
  $(document).ready(function(){
    // Only show options for either C2DM or GCM.
  	$('input#edit-push-notifications-google-type-0').click(function(e) {
      $('fieldset#edit-c2dm-credentials').show();
      $('fieldset#edit-gcm-credentials').hide();
  	});
    
  	$('input#edit-push-notifications-google-type-1').click(function(e) {
      $('fieldset#edit-c2dm-credentials').hide();
      $('fieldset#edit-gcm-credentials').show();      
  	});    
  });  
})(jQuery);