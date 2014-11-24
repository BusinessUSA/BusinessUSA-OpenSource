<?php /*
    
    [--] PURPOSE [--]
    
    This script is used to get the result [data] from Views in JSON form. 
    This is useful when a JavaScript needs to get data from a View through an AJAX session.
    
    [--] IMPLEMENTATION [--]
    
    Note that this page may not return data in the structure you may initially be expecting. This script will return the same 
    structure/object that is returned by the PHP function views_get_view_result() - See documentation: 
    https://api.drupal.org/api/views/views.module/function/views_get_view_result/7
    
    When the $_GET[view] and $_GET[display] parameters are given, this script will return JSON. See example: 
    http://dev.business.usa.reisys.com/sys/ajax/views_get_view_result?view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100
    
    You can easily pull data from the above example with jQuery/AJAX by executing the JavaScript:
    
        var url = '/sys/ajax/views_get_view_result?view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100';
        jQuery.get(url, function (dataReturned) {
            consoleLog('Got response from /sys/ajax/views_get_view_result, the returned data is:');
            consoleLog(dataReturned);
            alert('See JavaScript console');
        });

    ProTip - When signed in as an admin, add the webuidebug=1 parameter to view the return of views_get_view_result() k_printed by 
    Kurumo in the browser to get a visual idea of the data [and data-structure] returned. See example: 
    http://dev.business.usa.reisys.com/sys/ajax/views_get_view_result?webuidebug=1&view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100
    
*/

    // Prerequisite parameters
    $requestValid = true;
    if ( empty($_GET['view']) || trim($_GET['view']) === '' ) {
        print "Missing <b>view</b> parameter in the HTTP-Get query. Please provide a View's machine-name.<br/>";
        $requestValid = false;
    }
    if ( empty($_GET['display']) || trim($_GET['display']) === '' ) {
        print "Missing <b>display</b> parameter in the HTTP-Get query. Please provide a Display's machine-name that exists within the given View ({$_GET['view']}).<br/>";
        $requestValid = false;
    }
    
    // If (we have all the required parameters to run this functionality) ...
    if ( $requestValid === true ) {
    
        // Gather parameters - The View's name and display-name
        $viewMachineName = $_GET['view'];
        $displayMachineName = $_GET['display'];
        
        // Gather parameters - Any parameters to be queried with the view
        $viewParameterList = array();
        $paramId = 1;
        while ( isset($_GET["param$paramId"]) ) {
            $viewParameterList[] = $_GET["param$paramId"];
            $paramId++;
        }
        
        // Gather parameters - Pager value(s)
        if ( empty($_GET['pages']) ) {
            // No pager-value given in query, we shall assume this is a request for the first page of results from this view only
            $pages = array(0); // The pager-value of 0 means the first page [of the view]
        } else {
            // A pager-value parameter has been given, this should either be a number, or a list of numbers separated by commas
            if ( is_numeric($_GET['pages']) ) {
                // It appears this is a request for a single page
                $pages = array( intval($_GET['pages']) );
            } else {
                // It appears this is a request for multiple pages
                $pages = array();
                foreach ( explode(',', $_GET['pages']) as $page) { // to ensure integer sanitization
                    $pages[] = intval($page); // to ensure integer sanitization
                }
            }
        }
        
        // Gather parameters - Pager merge mode
        if ( !isset($_GET['mergepages']) ) {
            $mergepages = 1; // Default to 
        } else {
            $mergepages = intval( $_GET['mergepages'] );
        }
        
        // Get the results of the View ($viewMachineName) under the display ($displayMachineName) with the parameters ($viewParameterList) for each page in $pages
        $viewResultPages = array(); // Storage for View-results, seperated by page
        foreach ( $pages as $page ) {
            $myview = views_get_view($viewMachineName);
            $myview->set_display($displayMachineName);
            $myview->set_arguments($viewParameterList);
            $myview->set_current_page($page);
            $myview->pre_execute();
            $myview->execute();
            $viewResultPages[$page] = $myview->result; // Effectively, this returns views_get_view_result($viewMachineName, $displayMachineName, $viewParameterList[0], $viewParameterList[1], $viewParameterList[2], $viewParameterList[3], [...]) for the given $page
        }
        
        // Based on $mergepages, we shall either return View results separated by page, or unseparated
        if ( $mergepages === 1 ) {
            $viewResults = array();
            foreach ($viewResultPages as $viewResultPage ) {
                $viewResults = array_merge($viewResults, $viewResultPage);
            }
        } else {
            $viewResults = $viewResultPages;
        }
        
        // Check if we are in Web-UI-Debug mode
        if ( !empty($_GET['webuidebug']) && intval($_GET['webuidebug']) === 1 ) {
            
            // We are in Web-UI-Debug mode, use Kurumo to print $viewResults instead of just returning JSON
            dsm($viewResults); // Triggers Kurumo functionality (this function is part of the devel module)
            print '<b>In Web-UI-Debug mode</b>. See above for data and structure, see below for JSON output.';
            print '<textarea style="width: 100%; min-height: 500px;">' . json_encode($viewResults) . '</textarea>';
            print '<br/><br/><hr/>';
            
        } else {
        
            // We want to only spit out JSON, destroy whatever data/html that is about to be returned by this PHP thread (previous set, like the BUSA logo/main-menu/etc)
            header('Content-Type: application/json');
            
            // Spit out the results retrieved by views_get_view_result() as JSON
            $json = json_encode($viewResults);
            print $json;
            
            // Terminate this PHP thread
            exit();
        }
    }
    
?>

<div class="not-admin-only">
    <b>Sign in as a administrator to view usage and dev-notes.</b>
</div>

<div class="admin-only">
    <?php dsm('Note: This functionality is stored in the file: ' . __FILE__); ?>
    This script is used to get the result [data] from Views in JSON form. 
    This is useful when a JavaScript needs to get data from a View through an AJAX session.<br/>
    <br/>
    Note that this page may not return data in the structure you may initially be expecting. This script will 
    return the same structure/object that is returned by 
    <a href="https://api.drupal.org/api/views/views.module/function/views_get_view_result/7">
        views_get_view_result()
    </a> in PHP.<br/>
    <br/>
    When the <i>view</i> and <i>display</i> HTTP-Get parameters are given, this script will return JSON. 
    Refer to <a href="/sys/ajax/views_get_view_result?view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100">
    this example</a>.<br/>
    <br/>
    You can easily pull data from the above example with jQuery/AJAX by executing:<br/>
    <textarea style="width: 100%; min-height: 120px;">
        var url = '/sys/ajax/views_get_view_result?view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100';
        jQuery.get(url, function (dataReturned) {
            consoleLog('Got responce from /sys/ajax/views_get_view_result, the returned data is:');
            consoleLog(dataReturned);
            alert('See JavaScript console');
        });
    </textarea>
    <br/>
    ProTip - When signed in as an admin, add the <b>webuidebug=1</b> parameter to view the return of 
    views_get_view_result() k_printed by Kurumo in the browser to get a visual idea of the data 
    [and data-structure] returned. See <a href="/sys/ajax/views_get_view_result?webuidebug=1&view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100">
    this as an example (requires admin privileges)</a>.
    <br/>
    <br/>
    <b>Parameter list:</b><br/>
    <ul>
        <li>
            <b>view</b> (<i>Required</i>) - The machine name of the target View
        </li>
        <li>
            <b>display</b> (<i>Required</i>) - The machine name of the display of the target View to execute
        </li>
        <li>
            <b>param1</b> - The first parameter to execute with the View
        </li>
        <li>
            <b>param2</b> - The second parameter to execute with the View
        </li>
        <li>
            <b>param3</b> - The third parameter to execute with the View
        </li>
        <li>
            <b>param#</b> - The #th parameter to execute with the View (etc.)
        </li>
        <li>
            <b>pages</b> (<i>default: 0</i>) - The page number (pager-value) of the view to return. 
            This can either be a number stating which page to return (&pages=0 for the first page only, 
            &pages=1 for the second page only, &pages=2 for the third page only), or a comma 
            separated string of numbers stating which pages to return (&pages=3,4,5 for pages 3, 4, and 5).
        </li>
        <li>
            <b>mergepages</b> (<i>default: 1</i>) - Weather or not to return results across all pages referenced 
            by the <b>pages</b> parameter in an single array, or an array of results for each page.
            (by default will return an array of results across all pages.) Use &mergepages=0 to get a separate 
            array of results for each page.
        </li>
    </ul>
</div>
