<?php


$urlPath = request_uri(); // For docs on this funtion, review https://api.drupal.org/api/drupal/includes!bootstrap.inc/function/request_uri/7
$urlPath = strtok($urlPath, '?'); // Crop out the URL-query (anything after and including the ? in a URL path) - taken from Drupal8's version of request_uri() - https://api.drupal.org/api/drupal/core!includes!bootstrap.inc/function/request_uri/8
$urlPath = ltrim($urlPath, '/'); // Ensures that the $urlPath will NEVER begin with a slash (/)
$urlParams = $_SERVER['QUERY_STRING'];
switch ( strtolower(($urlPath)) ) {
    case 'browseregulations':
        $breadcrumb ='Find Regulations Wizard';
        break;
    case 'start-a-business':
        $breadcrumb ='Start a Business Wizard';
        break;
    case 'find-opportunities':
        $breadcrumb ='Find Opportunities Wizard';
        break;
    case 'taxes-and-credits':
        $breadcrumb ='Learn About Taxes and Credits Wizard';
        break;
    case 'veterans':
        $breadcrumb ='Browse Resources for Veterans Wizard';
        break;
    case 'access-financing':
        $breadcrumb = 'Access Financing Wizard';
        break;
    case 'begin-exporting':
        $breadcrumb = 'Begin Exporting Wizard';
        break;
    case 'expand-exporting':
        $breadcrumb = 'Expand Exporting Wizard';
        break;
    case 'healthcare':
        $breadcrumb = 'Learn About New Health Care Changes Wizard';
        break;
    case 'disaster-assistance':
        $breadcrumb = 'Seek Disaster Assistance Wizard';
        break;
    case 'select-usa':
        $breadcrumb = 'Invest in the USA Wizard';
        break;
    case 'jobcenter-wizard':
        $breadcrumb = 'Help with Hiring Employees Wizard';
        break;
    case 'find-green-opportunities':
        $breadcrumb = 'Find Green Opportunities Wizard';
        break;
    case 'export':
        $breadcrumb ='Explore Exporting';
        break;
    case 'quick-facts':
        $breadcrumb = 'Quick Facts';
        break;
    case 'tour':
        $breadcrumb = 'Take a Tour';
        break;
    case 'federal-resources':
        $breadcrumb = 'Federal Resources';
        break;
    case 'link-to-us':
        $breadcrumb = 'Link to Business.USA.gov through Widgets';
        break;
    case 'about-us':
        $breadcrumb = 'About Us';
        break;
    case 'apis':
        $breadcrumb = 'APIs';
        break;
    case 'blog':
        $breadcrumb = 'Blogs';
        break;
    case 'find-resources':
        $breadcrumb = 'Find Resources';
        break;
    case 'training-materials':
        $breadcrumb = 'Training Portal';
        break;
    case 'training-materials/video/':
        $breadcrumb = 'Training Portal';
        break;
    case 'training-materials/chat/':
        $breadcrumb = 'Training Portal';
        break;
    case 'training-materials/online training/':
        $breadcrumb = 'Training Portal';
        break;
    case 'events':
        $breadcrumb = 'Events';
        break;
    case 'request-appointment-and-closest-resource-centers':
        $breadcrumb = '<a href="http://help.business.usa.gov">Support Center</a>' . ' » ' . 'Request an Appointment';
        break;
    case 'sitemap':
        $breadcrumb = 'Site Map';
        break;
    case 'sizeup':
        $breadcrumb = 'Size Up';
        break;
    case 'sbir-certification-tool':
        $breadcrumb = 'SBIR Certification Tool';
        break;
    default:

        if ( strpos($urlPath, 'search/site') !== false ) {
            // This is the search results page
            $urlArray = explode("/", $urlPath);
            $keyword = end($urlArray);
            if (strpos($urlParams, 'success_story')){
                $breadcrumb = '<a href="/search">Search</a>' . ' » ' . '<a href="/search/site">Site</a>' . ' » ' . 'Success Story';
            }
            else{
                $breadcrumb = '<a href="/search">Search</a>' . ' » ' . '<a href="/search/site">Site</a>' . ' » ' . urldecode($keyword);
            }
        } elseif (strpos($urlPath, 'search/node') !== false) {
            $breadcrumb = '<a href="/search">Search</a>' . ' » ' . '*';
        } elseif (strpos($urlPath, 'resource/') !== false) {
            $urlArray = explode("/", $urlPath);
            $title = end($urlArray);
            $breadcrumb = '<a href="/find-resources">Resources</a>' . ' » ' . ucwords(str_replace("-"," ",$title));
        }else {

            // Use the function menu_get_object() and arg() in order to determin if the user is on a content landing page
            // something like...
            // arg() documentation = https://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/arg/7
            $sysPath = arg();
            if ( $sysPath[0] == 'node' && !empty($sysPath[0]) ) { // Testes if the system path is something like "node/1234"
                // This is a content landing page
                $node = menu_get_object();
                $nodeContentType = $node->type;
                $nodeTitle = $node->title;
                $contentTypeDisplayValue = $nodeContentType;
                if ($nodeContentType == 'green_sbir_topic'){
                    $contentTypeDisplayValue = 'Green SBIR Topic';
                }else if ($nodeContentType == 'event'){
                    $contentTypeDisplayValue = 'Events';
                }
                $breadcrumb = '<a href="/search/site/%2A?f[0]=bundle%3A' . $nodeContentType . '">' . ucwords(str_replace("_"," ",$contentTypeDisplayValue)) . '</a>' . ' » ' . $nodeTitle;
            } else {
                // Unknown breadcrumb, assume a default breadcrumb?
                $breadcrumb = '';
            }
        }
        break;
}

$breadcrumb = breadcrumbMarkup($breadcrumb);

function breadcrumbMarkup($breadcrumb){
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $output .= '<div class="breadcrumb"><a href="/">Home</a>' . ' » '. $breadcrumb . '</div>';
    return $output;
}
