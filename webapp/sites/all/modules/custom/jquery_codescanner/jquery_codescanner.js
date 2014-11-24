
jquery_codescanner = {
    
    reportIssueOnPage: function (message) {
        var errHtml = String('<div class="messages error"><h2 class="element-invisible">Error message</h2>MESSAGE HERE</div>');
        errHtml = errHtml.replace('MESSAGE HERE', message);
        jQuery('#contentArea').prepend(errHtml);
    },
    
    reportIssueToServer: function (subject, message) {
        
        var apiUrl = '/jquery_codescanner/report?bypassCDNcache=' + Math.floor(Math.random() * 10000)
        var postData = {
            'subject': subject, 
            'message': message,
            'url': String(document.location.pathname)
        };
        
        jQuery.post(apiUrl, postData, function (retData) {
            if ( typeof retData != 'object' || typeof retData['success'] == 'undefined' ) {
                alert('Error - /jquery_codescanner/report API returned unexpected data');
            } else {
                if ( retData['success'] == false ) {
                    alert('Error - jquery_codescanner report-callback API failed with message: ' + retData['message']);
                }
            }
        });
        
      if ( typeof console != 'undefined' ) {
        console.log('A jquery_codescanner report was sent back to the server with the message: ' + subject + ' - ' + message);
      }
        
    }
    
};