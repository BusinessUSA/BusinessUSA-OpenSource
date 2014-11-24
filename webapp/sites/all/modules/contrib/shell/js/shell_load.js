// This is meant to load when the page loads.

Drupal.behaviors.shellStartup = {
attach: function (context, settings) {
  
  jQuery("#shell-input-field").focus();  // give focus on load.
  
  jQuery('#shell-input-field').bind('keydown', function(e) {
    if(e.keyCode==13){
      // Enter pressed...
      shellSendCommand("");
      shellHistoryPointer = shellHistory.length;
      return false;
    }
    if (e.keyCode == 38) {
      // Pressed up.  Move up through the history.
      shellHistoryPointer--;
      if (shellHistoryPointer < 0) {
        shellHistoryPointer = 0;
      }
      shellDisplayInputHistory();
    }
    if (e.keyCode == 40) {
      // Pressed down.  Move down through the history.
      shellHistoryPointer++;
      if (shellHistoryPointer >= shellHistory.length) {
        shellHistoryPointer = shellHistory.length;
        // If we reached the bottom, then clear it out.
        jQuery("#shell-input-field").val("");
      } else {
        shellDisplayInputHistory();
      }
    }
    if(e.keyCode == 9) {
      // Tab pressed...
      shellSendCommand("__pressed_tab");
      shellHistoryPointer = shellHistory.length;
      return false;
    }

    
  });
  
}
}