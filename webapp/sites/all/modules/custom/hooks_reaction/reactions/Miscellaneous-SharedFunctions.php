<?php
/*

    [--] PURPOSE [--]
    
    This file is used to store custom-code/functions that will/may be used throughout
    different modules, pages. Please only place functions in here if there is not a single 
    other/better place to put them.
    
    Functions here are meant to be here because they are shared across different mods.
    
    [--] IMPLEMENTATION [--]
    
    Please keep in mind that the order of inclusion/execution of this script versus others 
    may result in functions in this file not-yet being defined when another file is executed.
    It is always a good practice to use HOOK_init() to avoid issues like this.

*/

/** bool isValidHTML(string)
 *
 *  Detects if the given HTML is valid (without any structure errors)
 *  
 *  Malformed HTML may still be loaded with PHP's DOMDocument class, all this 
 *  function checks is if there are ANY errors or malformed HTML in the given string.
 */ 
if ( function_exists('isValidHTML') === false ) {
    function isValidHTML($givenHTML) {
        
        // Return buffer this function will return
        $ret = null;
        
        // Detect libxml errors within this function, libxml-errors may get triggered from calling DOMDocument as described in http://us1.php.net/manual/en/class.domdocument.php
        $prevLibXmlUseInternalErrorsFlag = libxml_use_internal_errors();
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        
        // Try loading the given HTML with DOMDocument - http://us1.php.net/manual/en/class.domdocument.php
        $doc = new DOMDocument();
        @$doc->loadHTML($givenHTML);
        
        // Check if there were any libxml errors triggered with the last call of DOMDocument::loadHTML()
        if ( count(libxml_get_errors()) === 0 ) {
            $ret = true;
        } else {
            $ret = false;
        }
        
        // Assume the HTML is mal formed if there are any unclosed tags 
        if ( allTagsClosedInHTML($givenHTML) === false ) {
            $ret = false;
        }
        
        // Restore libxml error flag to its previous state
        libxml_use_internal_errors($prevLibXmlUseInternalErrorsFlag);
        
        // Return 
        return $ret;
    }
}

/** bool allTagsClosedInHTML(string)
 *
 *  Detects if all of the HTML tags in the given string are closed.
 *  This is a simple function that does not support (may screw 
 *  up with), HTML comments, and malformed HTML.
 *
 *  Really all this function does is count how many tags are opened,
 *  and how many are closed, and compares the count.
 */ 
if ( function_exists('allTagsClosedInHTML') === false ) {
    function allTagsClosedInHTML($html) {
        
        // Put all opened tags into an array
        preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
        $openedtags = $result[1];
        $len_opened = count ( $openedtags );
        
        // Put all closed tags into an array
        preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
        $closedtags = $result[1];
        $len_closed = count ( $closedtags );
        
        # All tags are closed
        if ( $len_opened == $len_closed ) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * string cssFriendlyString(string inputString[, string/array $charactersToRemove, bool forceLowerCase = true])
 *
 * alias to getEasyCompareString() since these 2 functions require the same functionality.
 * See tha function for documentation.
 */
if ( function_exists('cssFriendlyString') === false ) {
    function cssFriendlyString($inputString, $charactersToRemove = ' -_/!?@#$%^&*()[]{}<>\'"', $forceLowerCase = true, $trimString = true) {
        return getEasyCompareString($inputString, $charactersToRemove, $forceLowerCase, $trimString);
    }
}

/**
 * string getEasyCompareString(string inputString[, string/array $charactersToRemove, bool forceLowerCase = true])
 *
 * Returns the given string with certain characters removed, and converted to lowercase if desiered.
 * This makes things easier to compare two strings in certain situations.
 */
if ( function_exists('getEasyCompareString') === false ) {
    function getEasyCompareString($inputString, $charactersToRemove = " -_/\\!?@#$%^&*'\"()[]{}<>", $forceLowerCase = true, $trimString = true, $stripUnicodeCharacters = true, $replaceCharsWith = '', $killRepeatingReplacements = true) {
        
        $ret = $inputString;
        
        if ( is_null($charactersToRemove) ) {
            $charactersToRemove = " -_/\\!?@#$%^&*'\"()[]{}<>";
        }
        
        if ( !is_array($charactersToRemove) ) {
            $charactersToRemove = str_split($charactersToRemove);
        }
        $charactersToRemove[] = '%20';
        
        foreach ( $charactersToRemove as $charToRemove ) {
            $ret = str_replace($charToRemove, $replaceCharsWith, $ret);
        }
        
        if ( $forceLowerCase ) {
            $ret = strtolower( $ret );
        }
        
        if ( $trimString ) {
            $ret = trim( $ret );
        }
        
        if ( $stripUnicodeCharacters ) {
            $ret = stripUnicode($ret, $replaceCharsWith);
        }
        
        if ( $replaceCharsWith !== '' && $killRepeatingReplacements == true ) {
            while ( strpos($ret, $replaceCharsWith . $replaceCharsWith) !== false ) {
                $ret = str_replace($replaceCharsWith . $replaceCharsWith, $replaceCharsWith, $ret);
            }
        }
        
        return $ret;
    }
}

/**
 * string stripUnicode(string $inputString)
 *
 * Returns $inputString with all Unicode characters stripped
 */
if ( function_exists('stripUnicode') === false ) {
    function stripUnicode($inputString, $replaceUnicodeCharsWith = '') {
        
        $removeCharacters = array();
        for ( $x = strlen($inputString) - 1 ; $x > -1 ; $x-- ) {
            $thisChar = $inputString[$x];
            $charCode = ord($thisChar);
            if (
                ( 96 < $charCode && $charCode < 123 )
                || ( 64 < $charCode && $charCode < 91 )
                || ( 47 < $charCode && $charCode < 58 )
            ) {
                // Then this is a character, a-z, A-Z, or 0-1
            } else {
                $removeCharacters[$thisChar] = $thisChar;
            }
        }
        
        $inputString = str_replace(array_values($removeCharacters), $replaceUnicodeCharsWith, $inputString);
        
        return $inputString;
    }
}

/**
 * string getLinkAbsolutePath(string $requestFromPageURL, string $targetHref)
 *
 * Returns an absolute-path URL, converting the given relative path to a full path.
 * This is meant to be used to resolve the targets of anchors, weather they be on BusinessUSA, or on another domain.
 */
if ( function_exists('getLinkAbsolutePath') === false ) {
    function getLinkAbsolutePath($requestFromPageURL, $targetHref) {
        
        if ( strval($targetHref) === '' ) {
            return '';
        }
        
        if ( substr($targetHref, 0, 1) === '#' ) {
            return $targetHref;
        }
        
        $urlInfo = parse_url($requestFromPageURL);
        $pathInfo = pathinfo($urlInfo['path']);
        
        if ( substr($targetHref, 0, 1) === '/' ) {
            return 'http://' . $urlInfo['host'] . $targetHref;
        }
        
        if ( substr($targetHref, 0, 2) === './' ) {
            $targDir = $pathInfo['dirname'];
            if ( substr($requestFromPageURL, -1) === '/' ) {
               $targDir = str_replace( '//', '/', $targDir . '/' . $pathInfo['filename'] );
            }
            return 'http://' . $urlInfo['host'] . $targDir . substr($targetHref, 1);
        }
        
        if ( substr($targetHref, 0, 3) === '../' ) {
            if ( substr($requestFromPageURL, -1) === '/' ) {
                $targDir = explode('/', rtrim($urlInfo['path'], '/'));
            } else {
                $targDir = explode('/', $pathInfo['dirname']);
            }
            array_pop($targDir);
            $targDir = implode('/', $targDir);
            return 'http://' . $urlInfo['host'] . $targDir . substr($targetHref, 2);
        }
        
        if ( substr($targetHref, 0, 7) === 'http://' ) {
            return $targetHref;
        }
        
        if ( substr($targetHref, 0, 8) === 'https://' ) {
            return $targetHref;
        }
        
        if ( substr($targetHref, 0, 7) === 'mailto:' ) {
            return $targetHref;
        }
        
        return 'http://' . $urlInfo['host'] . $pathInfo['dirname'] . '/' . $targetHref;
    }
}

/**
 * string curl_get_contents(string $url)
 *
 * Similar to file_get_contents(), only this will use curl for geting the html/data from target URLs
 * Meant to be used with URLs only (not local file-paths)
 */
if ( function_exists('curl_get_contents') === false ) {
    function curl_get_contents($url) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
        
    }
}

/**
 * string canonicalizeUrl(string $url)
 *
 * Normalizes a URL, for example from:
 *     http://www.example.com/something/../else
 * to:
 *     http://www.example.com/else
 */
function canonicalizeUrl($url) {
    $url = explode('/', $url);
    $keys = array_keys($url, '..');

    foreach($keys AS $keypos => $key) {
        array_splice($url, $key - ($keypos * 2 + 1), 2);
    }

    $url = implode('/', $url);
    $url = str_replace('./', '', $url);
    return $url;
}

/**
 * array findNodesByTitle(string $title, string/array $underTheContentTypeOf = false)
 *
 * Pings the node table in MySQL to find nodes that match a certain title.
 *
 * $underTheContentTypeOf may be passed as an array of strings
 * Value(s) in $underTheContentTypeOf may start with a ! to denote the content-type 
 * should NOT this value
 *
 * Returns an array of 
    array(
        'nid' => Node ID,
        'type' => Content Type Machine Name,
        'status' => Is Published (int),
    )
 */
function findNodesByTitle($title, $underTheContentTypeOf = false) {
    
    $ret = array();
    
    $title = trim($title);
    if ( $title === '' ) {
        return array();
    }
    
    $ctypeSql = '';
    if ( $underTheContentTypeOf !== false ) {
        
        if ( is_string($underTheContentTypeOf) ) {
            $underTheContentTypeOf = array($underTheContentTypeOf);
        }
        
        $inTypes = array();
        $notInTypes = array();
        
        foreach ( $underTheContentTypeOf as $ctype ) {
            if ( substr($ctype, 0, 1) === '!' ) {
                $notInTypes[] = substr($ctype, 1);
            } else {
                $inTypes[] = $ctype;
            }
        }
        
        $ctypeSql = '';
        
        if ( count($inTypes) > 0 ) {
            $appendSql = "'" . implode("', '", $inTypes) . "'";
            $appendSql = " AND n.type IN ({$appendSql}) ";
            $ctypeSql .= $appendSql;
        }
        
        if ( count($notInTypes) > 0 ) {
            $appendSql = "'" . implode("', '", $notInTypes) . "'";
            $appendSql = " AND n.type NOT IN ({$appendSql}) ";
            $ctypeSql .= $appendSql;
        }
        
    }
    
    $escappedTitle = str_replace("\\", '', $title);
    $escappedTitle = str_replace(chr(39), chr(92) . chr(39), $escappedTitle); // Escape all single-quotes in $title ( replace all ' with \' ) - SQL sanitization
    $query = "
        SELECT 
            n.nid AS 'nid', 
            n.type AS 'type',  
            n.status AS 'status'
        FROM node n
        WHERE 
            title = '{$escappedTitle}' 
            {$ctypeSql}
            AND status>0 
    ";
    $results = db_query($query);
    foreach ($results as $record) { // For each nid found in the node table in the database that has $title as the title
        $ret[$record->nid] = array(
            'nid' => $record->nid,
            'type' => $record->type,
            'status' => $record->status
        );
    }
    
    return $ret;
}

/**
 * array scandir_recursive(string $path)
 *
 * Given a path on the local file-system, will scan the given directory and returl all files.
 * This is numch like the scandir() function, but this one is recursive - it will scann all 
 * subdirectories. This function may follow symlinks.
 */
function scandir_recursive($dir, $prefix = '') {
    $dir = rtrim($dir, "\\/");
    $result = array();

    foreach ( scandir($dir) as $f ) {
        if ( $f !== '.' and $f !== '..' ) {
            if ( is_dir($dir . '/' . $f) ) {
                $result = array_merge($result, scandir_recursive($dir . '/' . $f, $prefix . $f . '/'));
            } else {
                $result[] = $prefix . $f;
            }
        }   
    }

    return $result;
}
