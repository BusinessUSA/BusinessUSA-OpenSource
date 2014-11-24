<?php

/** boolean linkIs404Error(string)
  * Test if a given URL points to a page which will return a HTTP-404 Error (Page Not Found)
  * Returns TRUE when the given url-string is a malformed URL or points to a 404-Error-page
  * Returns FALS when the target url-string is a link to a valid page
  */
if ( function_exists('linkIs404Error') === false ) {
    function linkIs404Error($url, $follow = true) {
        
        $heads = @get_headers($url, 1);
        
        // Follow moved pages
        if ( $follow === true && strpos($heads[0], 'Moved') !== false && empty($heads['Location']) === false ) {
            return linkIs404Error($heads['Location'], $follow);
        }
        
        if ( $heads === false || strpos($heads[0], '404') !== false ) {
            return true;
        } else {
            return false;
        }
        
    }
}