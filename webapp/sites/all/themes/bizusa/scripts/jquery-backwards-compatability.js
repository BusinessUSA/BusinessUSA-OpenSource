/*
    
    This script is meant to be called in order apply certain bug-killers that arise from jQuery version mis-match issues.
    This script should be executed IMMEDIATELY after jQuery core is loaded 
    
*/

// Hack/BugKiller for jQuery > 1.9 with apachesolr_autocomplete.js
if ( typeof jQuery.browser == 'undefined' ) {
    jQuery.browser = {
        opera: false,
        msie: ( window.navigator.userAgent.indexOf('MSIE ') != -1 )
    };
}