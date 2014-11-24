<?php
/**
 * Created by PhpStorm.
 * User: naga.tejaswini
 * Date: 4/4/14
 * Time: 6:00 PM
 */
if ( empty($_GET['invoke']) || intval($_GET['invoke']) !== 1 ) {

    print 'Use ?invoke=1 in the URL-query to execute functionality. Returns json.<br/>';
    print 'Use ?invoke=1&debug=1 in the URL-query to see what this script will output in a human-readable fassion (kprint_r).<br/>';
    print 'Use no URL-query (like you did now) to be abel to edit this Drupal-"Basic Page". Requieres you to be logged in as an admin.<br/>';
    return; // Cease rendering this script
}

/** array getAllContentFromApiDotTradeDotGov(string $tradeContentToConsume)
 * *
 *  This function is used to grab ALL Events from the API, since the API
 *  seems to only return a max of 100 content-items at a time.
 *
 *  i.e. The following API call only returns 100 results despite the request limit for any number eg.5000:
 *   http://api.trade.gov/trade_events/search?country=BR&size=1&offset=1
 *
 *  This function understands this limit, and returns all content-items.
 *
 *  Returns an array in the same root-structure as the array given from the API.
 *  i.e. structure of:
    return array(
    'results' => array(
 *      [all results from the API across all pages/offsets]
 *      [total]
 *     );
 */

if ( !function_exists('_getAllContentFromApiDotTradeDotGov') )
{
    function _getAllContentFromApiDotTradeDotGov($tradeContentToConsume = 'trade_events')
    {
        $offset = 0;
        $tradeDotGovArticles = array();
        while ( true )
        { // Start an infinite loop, we will break out of this loop programmatically

        // Ensure the PHP thread does not time-out
            set_time_limit(900);
        // Try to grab 100 articles from api.trade.gov/$tradeContentToConsume/search starting at $offset
            $jsonString = file_get_contents("http://api.trade.gov/$tradeContentToConsume/search?offset=$offset&size=100");
            $jsonData = json_decode($jsonString, true);
        // Check if we got (any) results/articles from $offset
            if ( empty($jsonData['results']) || count($jsonData['results']) === 0 )
            {
            // If we got no results/articles, then that means there are no more articles to pull from api.trade.gov/$tradeContentToConsume/search
                break;
            }

            else {
            // 100 (or less) articles were returned from this API call, we shall merge this result-array into $tradeDotGovArticles
                $tradeDotGovArticles = array_merge($tradeDotGovArticles, $jsonData['results']);
                $offset += 100;
                // Increment $offset by 100 so that we can grab the NEXT 100 articles offered by api.trade.gov/$tradeContentToConsume/search
            }
        }
            // Return array('results', 'total'), the same structure as returned from the API as seen here: http://api.trade.gov/$tradeContentToConsume/search?offset=0&size=100
     return array(
        'results' => $tradeDotGovArticles,
        'total' => count($tradeDotGovArticles)
        );
    }
}
    $results = _getAllContentFromApiDotTradeDotGov('trade_events');

    if ( empty($_GET['debug']) || intval($_GET['debug']) !== 1 ) {
        @ob_end_clean();
        while ( @ob_end_clean() );
        print json_encode($results);
        exit();
    } else {
        kprint_r( $results );
    }





