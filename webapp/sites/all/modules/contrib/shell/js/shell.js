
var shellHistory = new Array();
var shellHistoryPointer = 0;
var shellCwd = "";
var shellPopupCount = 0;


function shellSendCommand(sendData) {
  // Add what we typed to the history
  var bool_pressed_tab = false;
  
  if (sendData != "__pressed_tab") {
    shellHistory.push(jQuery("#shell-input-field").val());
  }
  
  // Some servers have a problem sending certain commands through the POST,
  // like "cd .." or "wget."  To get around this, we will encode the string
  // the user is trying to send...

  if (sendData == "") {
    sendData = jQuery("#shell-input-field").val();
    sendData = "command=" + shellEncode(sendData);
    // insert a history item of what the player typed
    shellInsertHistory("<div class='user-command'>&gt; " + jQuery("#shell-input-field").val() + "</div>", true);
    //  clear out the message box.    
    jQuery("#shell-input-field").val("");
  }
  if (sendData == "__pressed_tab") {
    sendData = jQuery("#shell-input-field").val();
    sendData = "command=" + shellEncode(sendData);
    sendData = sendData + "&pressed_tab=yes";
  }
  
  // Add in extra information so we can validate this submission in PHP
  // (to prevent CSRF):
  sendData = sendData + "&form_token=" + jQuery("#shell-display-form input[name=form_token]").val();

  // Add the current working directory (cwd) to the sendData, but encode it just like the command.
  sendData = sendData + "&shell_cwd=" + shellEncode(shellCwd);

  // Send our sendData to the shell module via ajax.
  var p = Drupal.settings.basePath + "shell/ajax-send-command";  
  jQuery.post(p, sendData, function(data) {

    // Capture the returned shell_cwd so we can keep track of what the
    // user's current working directory is.
    shellCwd = data.shell_cwd;
    
    // Text was sent back to go on the screen.
    if (data.text != "") {
      shellInsertHistory(data.text, true);
    } 
    
    // A change to the input field was sent back
    // (probably because the user pressed TAB)
    if (data.input_field != "") {
      //var temp = jQuery("#shell-input-field").val();
      jQuery("#shell-input-field").val(data.input_field);
    }
    
  } , "json");

  jQuery("#shell-input-field").focus();
  
}




function shellInsertHistory(str, scroll_bottom) {
  
  var temp = jQuery("#shell-screen-history").html() + str;
  // Append to the bottom
  jQuery("#shell-screen-history").html(temp);
  // make it scroll to bottom.
  if (scroll_bottom) {
    jQuery("#shell-screen-history").attr({ scrollTop: jQuery("#shell-screen-history").attr("scrollHeight") });
  }    
}



function shellDisplayInputHistory() {
  // Set the input field to whatever the shellHistoryPointer is pointing to.
  jQuery("#shell-input-field").val(shellHistory[shellHistoryPointer]);
}


// This is a function to provide base64 encoding as well as URI encoding, 
// which we will use to
// encode the commands we are sending through the POST.
// This function is based off of a similar public domain
// function by Tyler Akins -- http://rumkin.com
function shellEncode (input) {
  var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	var output = "";
	var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
	var i = 0;

	while (i < input.length) {

		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);

		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;

		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}

		output = output +
		keyStr.charAt(enc1) + keyStr.charAt(enc2) +
		keyStr.charAt(enc3) + keyStr.charAt(enc4);

	}

	return encodeURIComponent(output);
}


function shellPopupShellWindow() {
  shellPopupCount++;
  var x = window.open (Drupal.settings.basePath + "shell-popup", "shellPopupWindow" + shellPopupCount,
           "status=1,toolbar=1,scrollbars=1,resizable=1,width=600,height=550");   
  
  return false;
}
