<?php /*
    
    [--] WARNING [--]
    
    This script poses a major security risk if configured incorrectly! Please explose ONLY the defined PHP functions 
    which cannot be abused!
    
    [--] PURPOSE [--]
    
    This script is used to get allow JavaScript functions to call specific/individual PHP functions through an ajax request.
    This script shall trigger the requested function (by name) and return whatever the PHP functions returns.
    
    
    [--] IMPLEMENTATION [--]
    
    When the $_REQUEST[function] parameter is given, this script will execute the given function [name]
    
    If a $_REQUEST[params] variable is given, then it is expected to be a [json encoded] array, containing 
    the parameters to send into the target PHP function.
    
    This script will return a JSON-encoded array of:
    array(
        time => The time it took in microseconds (float) between calling the function, and the return of the function
        return => Whatever the function has returned
        print => Captured output/stdout (captured data from print(), echo(), etc.)
    )
    
*/

$exposedFunctionList = array( /* PLEASE EXPLOSE ONLY PHP FUNCTIONS WHICH CANNOT BE ABUSED! */
    'getLatLongFromZipCode' => true,
    'getZipCodeFromLatLong' => true,
    'incrementShareCounter' => true,
    'emailWizardExcelResultsSpreadsheet' => true,
    'createQuickLinkTarget' => true,
    'codereview_getJiraComments' => true,
    'codereview_postJiraComment' => true,
    'codereview_postCodeReviewComment' => true,
    'trackNewUserSearch' => true,
    'node_load' => true,
);

if ( empty($_REQUEST['function']) ) {
    $msg = "Please view the comments in " . basename(__FILE__ ). " for documentation and usage of this script";
    print $msg;
    return $msg;
}

$functName = $_REQUEST['function'];

if ( !isset($exposedFunctionList[$functName]) || $exposedFunctionList[$functName] === false ) {
    $msg = "REJECTED - The requested function name {$functName} was not found in the allowed-functions-list for this script. Coder Bookmark: CB-BFZVJ81-BC";
    print $msg;
    error_log($msg);
    return $msg;
}

if ( !function_exists($functName) ) {
    $msg = "ERROR - Function {$functName} is not defined in the system!";
    print $msg;
    return $msg;
}

$params = array();
if ( isset($_REQUEST['params']) ) {

    $params = json_decode($_REQUEST['params']);
    if ( is_null($params) ) { // If json_decode() failed, it may be due to url-encoding of $_REQUEST['params']
        $params = json_decode( urldecode($_REQUEST['params']) );
    }
    
    if ( !is_array($params) ) {
        $msg = "ERROR - The params argument given to this script is expected to be a JSON encoded array";
        print $msg;
        return $msg;
    }
}

header('Content-Type: application/json');

$startTime = microtime(true);
ob_start();
$capturedRet = @call_user_func_array($functName, $params);
$capturedOutput = ob_get_contents();
ob_end_clean();
$endTime = microtime(true);
if($capturedRet->type === 'event'){
    $startDate = new DateTime($capturedRet->field_event_date['und'][0]['value']);
    $startOffset = $startDate->getOffset() / 3600;
    $startHour = $startDate->format('G') + $startOffset;
    $startDate->setTime($startHour, $startDate->format('i'));
    if(!$capturedRet->field_event_date['und'][0]['value2'])
        $endDate = $startDate;
    else
        $endDate = new DateTime($capturedRet->field_event_date['und'][0]['value2']);
    $endOffset = $endDate->getOffset() / 3600;
    $endHour = $endDate->format('G') + $endOffset;
    $endDate->setTime($endHour, $endDate->format('i'));
    $capturedRet->field_event_date['und'][0]['value'] = $startDate->format('Y-m-d H:i:s');
    $capturedRet->field_event_date['und'][0]['value2'] = $endDate->format('Y-m-d H:i:s');
}

$returnJson = json_encode(
    array(
        'time' => ( $endTime - $startTime ),
        'return' => $capturedRet,
        'print' => $capturedOutput
    )
);
print $returnJson;
return $returnJson;








