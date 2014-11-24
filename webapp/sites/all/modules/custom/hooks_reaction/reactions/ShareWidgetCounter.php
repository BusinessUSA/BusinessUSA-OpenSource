<?php

if ( !function_exists('ensureShareCounterTableExists') ) {
    function ensureShareCounterTableExists() {
        db_query('
            CREATE TABLE IF NOT EXISTS sharecounter (
                url TEXT, 
                mail_counter INT, 
                facebook_counter INT, 
                twitter_counter INT, 
                linkedin_counter INT
            )
        ');
    }
}



if ( !function_exists('incrementShareCounter') ) {
    function incrementShareCounter($forURL, $realm) {

        ensureShareCounterTableExists();
        
        error_log('test');
        
        switch ( strtolower($realm) ) {
            case 'facebook':
                $setField = 'facebook_counter';
                break;
            case 'twitter':
                $setField = 'twitter_counter';
                break;
            case 'linkedin':
                $setField = 'linkedin_counter';
                break;
            case 'mail':
                $setField = 'mail_counter';
                break;
            default:
                return -1;
        }
        
        if ( getShareCounter($forURL, 'total') === 0 ) {
            db_query("
                INSERT INTO sharecounter 
                (url, facebook_counter, twitter_counter, linkedin_counter, mail_counter) 
                VALUES ('{$forURL}', 0, 0, 0, 0) 
            ");
        }



        $results = db_query("UPDATE sharecounter SET {$setField} = {$setField} + 1 WHERE url = '{$forURL}' ");
        
        return 'incremented';
    }
}

if ( !function_exists('getShareCounter') ) {
    function getShareCounter($forURL, $realm) {
        
        ensureShareCounterTableExists();
        
        $escapedForURL = str_replace(
            array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a"), 
            array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z"),
            $forURL)
        ;

        //Added for the Wizard results page issue, as the escaped url on the results page add /wizard-results in the url
        //hence no result is retrieved from database.
        //Code begins
        $wizstring = '/wizard-results';
        if (strpos($escapedForURL,$wizstring))
        {
            $escapedForURL = str_replace($wizstring, '',$escapedForURL);
        }
        //Code end
        //Code added for the Share counting when the query has some parameters
        $pos = strpos($escapedForURL, '?');
        //dsm($pos);
        if ($pos !== false) {

            $urlpath = explode('?',$escapedForURL);
            $escapedForURL = $urlpath[0];
        }
        //End Code

        $results = db_query("
            SELECT * 
            FROM sharecounter
            WHERE url = '{$escapedForURL}'
        ");

        //dsm($results);

        
        foreach ( $results as $record ) {
            switch ( strtolower($realm) ) {
                case 'facebook':
                    return intval( $record->facebook_counter );
                case 'twitter':
                    return intval( $record->twitter_counter );
                case 'linkedin':
                    return intval( $record->linkedin_counter );
                case 'mail':
                    return intval( $record->mail_counter );
                case 'total':
                    return getShareCounter($forURL, 'facebook') + getShareCounter($forURL, 'twitter') + getShareCounter($forURL, 'linkedin') + getShareCounter($forURL, 'mail');
                default:
                    return 0;
            }
            break;
        }
        
        return 0;
    }
}