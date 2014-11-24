<?php /*
    
    [--] PURPOSE [--]
    
    This script is used to get a rendered View's HTML and return it in an AJAX session
    
    [--] IMPLEMENTATION [--]
    
    Note that this page returns data from the views_embed_view() function.
    You can review the Drupal documentation for this function from:
    https://api.drupal.org/api/views/views.module/function/views_embed_view/7
    
    Only when the $_GET[view] and $_GET[display] parameters are given will this script will return/execute its functionality
    http://dev.business.usa.reisys.com/sys/ajax/views_embed_view?view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100
    
    You can easily pull data from the above example with jQuery/AJAX by executing the JavaScript:
    
        var url = '/sys/ajax/views_embed_view?view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100';
        jQuery.get(url, function (dataReturned) {
            consoleLog('Got response from /sys/ajax/views_get_view_result, the returned data is:');
            consoleLog(dataReturned);
            alert('See JavaScript console');
        });

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
        
        // Gather parameters - Pager value
        $page = 0; // assume request for the first page of results from this view 
        if ( empty($_GET['page']) ) {
            $page = intval( $_GET['page'] );
        }
        
        // Get this rendered-View's ($viewMachineName's) HTML under the display ($displayMachineName) with the parameters ($viewParameterList) 
        $myview = views_get_view($viewMachineName);
        $myview->set_display($displayMachineName);
        $myview->set_arguments($viewParameterList);
        $myview->set_current_page($page);
        $myview->pre_execute();
        $viewHTML = $myview->preview(); // Effectively, this returns views_embed_view($viewMachineName, $displayMachineName, $viewParameterList[0], $viewParameterList[1], $viewParameterList[2], $viewParameterList[3], [...]) for the given $page
        
        // Check if we are in Web-UI-Debug mode
        if ( !empty($_GET['webuidebug']) && intval($_GET['webuidebug']) === 1 ) {
            
            // We are in Web-UI-Debug mode, use Kurumo to print $viewResults instead of just returning JSON
            dsm($viewResults); // Triggers Kurumo functionality (this function is part of the devel module)
            print '<b>In Web-UI-Debug mode</b>. See above for data and structure, see below for JSON output.';
            print '<textarea style="width: 100%; min-height: 500px;">' . json_encode($viewResults) . '</textarea>';
            print '<br/><br/><hr/>';
            
        } else {
        
            // We want to only spit out JSON, destroy whatever data/html that is about to be returned by this PHP thread (previous set, like the BUSA logo/main-menu/etc)
            header('Content-Type: text/html');
            
            // Spit out the HTML retrieved by views_embed_view() 
            print $viewHTML;
            
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
    This script is used to get a rendered View's HTML and return it in an AJAX session
    <br/>
    Note that this page returns data from the views_embed_view() function.
    You can review the Drupal documentation for this function from:
    <a href="https://api.drupal.org/api/views/views.module/function/views_embed_view/7">
        views_embed_view()
    </a>
    <br/>
    Only when the $_GET[view] and $_GET[display] parameters are given will this script will return/execute its functionality
    Refer to <a href="/sys/ajax/views_embed_view?view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100">
    this example</a>.<br/>
    <br/>
    You can easily pull data from the above example with jQuery/AJAX by executing:<br/>
    <textarea style="width: 100%; min-height: 120px;">
        var url = '/sys/ajax/views_embed_view?view=closes_resource_center_retheme&display=resource_centers&param1=38.925872&param2=-77.389841&param3=100';
        jQuery.get(url, function (dataReturned) {
            consoleLog('Got responce from /sys/ajax/views_embed_view, the returned data is:');
            consoleLog(dataReturned);
            alert('See JavaScript console');
        });
    </textarea>
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
            <b>page</b> (<i>default: 0</i>) - The page number (pager-value) of the view to return
        </li>
    </ul>
</div>
