/* START: Landing page tracking */
/*
 The purpose of this code is to track when the given user access 5 or more content-landing pages, and
 once this occurs, inform origin. This is the back-end/tracking implementation of the
 "Users who  viewed this [content] also viewed:" implementation.
 */
jQuery(document).ready(function(){
  var isLandingPage = false;
  if ( jQuery('body').hasClass('node-type-program') ) { //Program
    isLandingPage = true;
  }
  if ( jQuery('body').hasClass('node-type-rules') ) { //Rules
    isLandingPage = true;
  }
  if ( jQuery('body').hasClass('node-type-data') ) { //Data
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-licenses-and-permits')){ //Licenses and Permits
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-export-gov-micro-site-page')){ //Export Article
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-appointment-office')){ //Resource Centers
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-blog')){ //Blog
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-state-resource')){ //State Resource
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-event')){ //Event
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-article')){ //Article
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-success-story')){ //Success Story
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-tools')){ //Tools
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-services')){ //Service
    isLandingPage = true;
  }
  if (jQuery('body').hasClass('node-type-knowledgebase-article')){ //Knowledge base Article
    isLandingPage = true;

  }
  if (jQuery('body').hasClass('node-type-quick-facts')){ //Quick Facts
    isLandingPage = true;
  }

  if ( isLandingPage == true ) {


    // Track the node IDs of this pages in a cookie - get this landing-pages node-ID from the body class
    var bodyClasses = jQuery('body').attr('class');
    var pos = bodyClasses.indexOf('page-node- page-node-');
    var nodeId = bodyClasses.substring(pos + 21); // The node ID is in the body's class list. It will always exist as #### in: page-node page-node- page-node-####
    nodeId = nodeId.split(' ')[0];

    // Track the node IDs of this pages in a cookie - load the cookie
    var landingPageTrackingCookieData = readCookie('landingPageTracking');
    if ( landingPageTrackingCookieData == null || landingPageTrackingCookieData == "" ) {
      var landingPageTracking = []; // Initialize an array
    } else {
      var landingPageTracking = JSON.parse(landingPageTrackingCookieData);
    }

    // Update, add to the array
    landingPageTracking.push(nodeId);

    // Save the cookie
    landingPageTrackingCookieData = JSON.stringify(landingPageTracking);
    createCookie('landingPageTracking', landingPageTrackingCookieData, 3);

    // Debug and verbosity
    consoleLog('Logged the action of the user hitting this landing page (with the node-ID of ' + nodeId + ')');

    // As we are tracking this information, if there are more then 5 landing-page hits to report, then inform the server (touch Origin with this information)
    //alert(landingPageTracking.length.toString());
    if (landingPageTracking.length >= 5) {
        var busaLandingPageReportURL = '/sys/tracking/landing-page-hits';
        
        // We use getCurrentUnixEpochTime() as the bypassAkamaiCacheKey because all we need to do in order to bypass Akamai cache, is to request a URL that does not currently exist in the Akamai cache
        var queryData = {
            'nids': landingPageTracking,
            'bypassAkamaiCacheKey': getCurrentUnixEpochTime()
        };
        
        // Send an HTTP-Get request to the server at the URL of busaLandingPageReportURL with the URL-query of queryData
        jQuery.get(busaLandingPageReportURL, queryData, function (data) {
        
            // The code within this function (the code here) is the event handeler for the completion of the HTTP-get request to busaLandingPageReportURL

            // Debug and verbosity
            consoleLog('More than 5 landing-pages were access by this user, this information has been send to the Origin server.');

            // Reset the landingPageTracking cookie back to a blank array`
            landingPageTrackingCookieData = [];
            createCookie('landingPageTracking', landingPageTrackingCookieData, 3);

        });
    }

  }
  /* END: Landing page tracking */
});
