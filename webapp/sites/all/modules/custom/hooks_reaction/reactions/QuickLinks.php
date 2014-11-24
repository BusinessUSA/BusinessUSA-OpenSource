<?php
/*

    [--] PURPOSE [--]
    
    The purpose of this functionality supplied in this file is to implement a 
    system of short-cut links, much like the functionality of bit.ly
    
    [--] IMPLEMENTATION [--]
    
    With the usage of createQuickLinkTarget() and getQuickLinkTarget(), target
    links are stored in a special table called quicklinks in the MySQL database.
    
    This script bypasses Drupal's database connection framework, and is 
    NOT dependant on any Drupal functions, since this script is designed to 
    be able to be executed/included outside of Drupal.
   
*/

if ( function_exists('createQuickLinkTarget') == false ) {
    function createQuickLinkTarget($targetURL, $httpPostData = array(), $linkMySQL = false) {

        // Connect to MySQL, bypass Drupal's database connection (since this function may be running without Drupal bootstrapped)
        if ( $linkMySQL === false ) {
            $linkMySQL = connectMySQL();
        }
        
        // Ensure the needed table exists in the database
        ensureQuickLinksTableExists($linkMySQL);
        
        // Check if this QuickLink/Bookmark already exists
        $quickLinkId = getQuickLinkId($targetURL, $linkMySQL);
        if ( $quickLinkId !== false && $quickLinkId !== 0 ) {
            // This QuickLink/Bookmark already exists, so return the id for this
            return $quickLinkId;
        }
        
        $strHttpPostData = serialize($httpPostData);
        $nowTime = time();
        mysql_query("
            INSERT INTO quicklinks 
            (url, post_data, created_time) 
            VALUES ('$targetURL', '$strHttpPostData', $nowTime) 
        ", $linkMySQL);
        
        return dechex( mysql_insert_id($linkMySQL) );
    }
}

if ( function_exists('getQuickLinkTarget') == false ) {
    function getQuickLinkTarget($linkHexId, $redirectNow = false, $linkMySQL = false, $incrementNavCounter = true) {
        
        // Return buffer, return false unless otherwise changed
        $ret = false;
        
        // Connect to MySQL, bypass Drupal's database connection (since this function may be running without Drupal bootstrapped)
        if ( $linkMySQL === false ) {
            $linkMySQL = connectMySQL();
        }
        
        // Ensure the needed table exists in the database
        ensureQuickLinksTableExists($linkMySQL);
        
        // The $linkHexId variable references an id in the quicklinks table, we expect this function to get the value of this id in hex notation
        $linkId = hexdec($linkHexId);
        
        // Find this bookmark in the quicklinks table
        $result = mysql_query("SELECT * FROM quicklinks WHERE id=$linkId", $linkMySQL);
        while ( $row = mysql_fetch_assoc($result) ) {
            $ret = $row['url'];
        }
        
        // Update the nav_count field for this entry in the quicklinks table
        if ( $incrementNavCounter && $ret !== false  ) {
            mysql_query("
                UPDATE quicklinks 
                SET nav_count = nav_count + 1 
                WHERE id = $linkId
            ", $linkMySQL);
        }
        
        // Redirect and termin this PHP thread/script if desiered 
        if ( $redirectNow && $ret !== false ) {
            header('Location: ' . $ret);
            exit();
        }
        
        // Return false when the target-id was not found in the database
        return $ret;
    }
}

if ( function_exists('getQuickLinkId') == false ) {
    function getQuickLinkId($url, $linkMySQL = false) {
        
        if ( $linkMySQL === false ) {
            $linkMySQL = connectMySQL();
        }
        
        ensureQuickLinksTableExists($linkMySQL);
        
        $result = mysql_query("
            SELECT * 
            FROM quicklinks
            WHERE url='$url'
        ", $linkMySQL);
        while ( $row = mysql_fetch_assoc($result) ) {
            return intval( $row['id'] );
        }
        
        // Return false when the target-id was not found in the database
        return false;
    }
}

if ( function_exists('ensureQuickLinksTableExists') == false ) {
    function ensureQuickLinksTableExists($linkMySQL = false) {
        
        if ( $linkMySQL === false ) {
            $linkMySQL = connectMySQL();
        }
        
        $sql = "
            CREATE TABLE  quicklinks (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                url VARCHAR( 767 ) NOT NULL ,
                post_data VARCHAR( 767 ) NULL COMMENT  'a php serialized array, where the key is the name, and the value is the post-data-value',
                created_time INT UNSIGNED NULL COMMENT  'a unix timestamp of when this record was created',
                nav_count INT UNSIGNED NOT NULL DEFAULT  '0' COMMENT  'a count of how many times origin has been hit to resolve this bookmark',
                INDEX (  url ,  post_data )
            )
        ";
        mysql_query($sql, $linkMySQL);
    }
}

/*
 * resource connectMySQL()
 * Connects to the MySQL database that this Drupal instance is/will-be using
 * This is meant to create a connection to the database that bypasses Drupal's db_query() and query alter hooks
 * This is also meant to be used in situations where a connection to the database is needed when Drupal is not fully boot-strapped
 */
if ( function_exists('connectMySQL') == false ) {
    function connectMySQL() {
        $dbAuth = $GLOBALS["databases"]["default"]["default"];
        $host = $dbAuth["host"];
        $user = $dbAuth["username"];
        $pass = $dbAuth["password"];
        $port = $dbAuth["port"];
        $db = $dbAuth["database"];
        if ( !empty($port) ) {
            $host .= ":" . $port;
        }
        $link = mysql_connect($host, $user, $pass);
        mysql_select_db($db, $link);
        return $link;
    }
}









