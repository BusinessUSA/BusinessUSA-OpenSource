<?php

$solrUrl = db_query("SELECT url FROM apachesolr_environment")->fetchField();
$solrServerDomainName = parse_url($solrUrl, PHP_URL_HOST);
if ( gethostbyname($solrServerDomainName) === $solrServerDomainName ) {
    drupal_set_message(
        '<b>You must log in as the administrator and <a href="/admin/config/search/apachesolr/settings">setup Solr</a> in order to use search functionality.</b>',
        'error',
        false
    );
    return 1;
}

//This is simply a place holder for the events-search path (which is handled by the apache solr module)
//we only have this here in order to have events-search.css
require 'events_functions.php';

$page = (int) $_POST['page'];
/**
 * Implements block_view_alter().
 * Adds events search filters, javascript to create array string, and manages showing previously selected elements
 * This is a bit tricky because we're using the apachesolr module to display the results and the url has to be
 * constructed in a particular way, so we have to hack the url slightly
 */
$vendorPath = libraries_get_path('vendor');
require "$vendorPath/solarium/solarium/bizusa_conf/init.php";
$client = new Solarium\Client($config);
if($_POST['keyword'] == 'Search Events' || $_POSTs['applySubmit'])   $_POST['keyword'] = '';
$events = getEvents($client, $_POST, $page);

$eventsCount = $events->getNumFound();

if($eventsCount === 0){
    echo '
    <br/>
    <h3>There are no events matching your selected criteria.</h3>
    <br/>';
}
else {
    echo "<div id='block-system-main' class='block block-system block-odd first last clearfix'>";
    echo "<div class='col-sm-12'>";
    $count = 0;
    foreach ($events as $event) {
        // the documents are also iterable, to get all fields
        $event = node_load($event['entity_id']);
        include('event-result.tpl.php');
    }
    echo "</div>";
    echo "</div>";
}

if($eventsCount > 0 ) print '<div class="item-list">' . printPaginationLinks($eventsCount, $page) . '</div>';
