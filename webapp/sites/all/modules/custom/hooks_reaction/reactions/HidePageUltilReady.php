<?php
/*
    [--] PURPOSE [--]
    
    The purpose of this script is to hide the page during its load process, and only 
    show the page when it is ready. This was requested by the client as certain 
    pages do not appear to be visually rendered appropriately until JavaScript 
    executes needed actions on the DOM
    
    [--] IMPLEMENTATION [--]
    
    This script shall add CSS that targets certain pages, and hides the body tag.
    Additional CSS is then used to undo/override the previous action when the 
    "is-ready" class has been added to the body tag, which is added 150 
    milliseconds after jQuery(document).ready()

*/

$nameOfThisScript = basename(__FILE__);

drupal_add_js(
    "
        /* The following is added from the {$nameOfThisScript} file */
        
        // Add the not-yet-ready class to the HTML and BODY tags
        document.getElementsByTagName('html')[0].className += ' not-yet-ready';
        if ( typeof document.body != 'undefined' && document.body != null ) {
            document.body.className += ' not-yet-ready';
        }
        
        jQuery(document).ready( function () {
            setTimeout( function () {
                jQuery('body').removeClass('not-yet-ready');
                jQuery('html').removeClass('not-yet-ready');
            }, 150);
        });
    ", 
    "inline"
);

if ( request_uri() === '/' ) {
    drupal_add_css(
        "
            /* The following is added from the {$nameOfThisScript} file */
            html.not-yet-ready, body.not-yet-ready {
                opacity: 0;
                -ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)'
            }
        ", 
        "inline"
    );
}