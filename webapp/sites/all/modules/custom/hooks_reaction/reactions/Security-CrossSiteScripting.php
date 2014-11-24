<?php

$ruri = strtolower( $_SERVER['REQUEST_URI'] );
$checkRURIs = array();
$checkRURIs[] = $ruri;
$checkRURIs[] = urldecode($ruri);
$checkRURIs[] = urldecode( str_replace( ' ', '', $ruri ) );

$bannedParameters = array(
    "onabort",
    "onblur",
    "onchange",
    "onerror",
    "onfocus",
    "onload",
    "onmouseover",
    "onmouseout",
    "onselect",
    "onsubmit",
    "onunload",
    "onfocusin",
    "onfocusout",
    "onactivate",
    "onclick",
    "onmousedown",
    "onmouseup",
    "onmouseover",
    "onmousemove",
    "onmouseout"
);

foreach ( $checkRURIs as $checkRURI ) {
    if ( 
        strpos($checkRURI, '<script>') !== false || 
        strpos($checkRURI, '</script>') !== false ||
        strpos($checkRURI, 'xlmns') !== false
    ) {
        xssAttackDetected();
    }
    foreach( $bannedParameters as $bannedParameter ) {
        if ( stripos($checkRURI, $bannedParameter) !== false ) {
            xssAttackDetected();
        }
    }
}

function xssAttackDetected() {

    @ob_end_clean();
    while (@ob_end_clean());
    
    @file_put_contents(
        'sites/default/files/debugXSS.log',
        "========== XSS ATTACK BLOCKED ==========" . "\nURL: " . request_uri() . "\nPOST: " . print_r($_POST, true) . "\n",
        FILE_APPEND
    );
    
    header("HTTP/1.0 403 Forbidden - XSS Detected");
    exit('Exiting - Cross-site-scripting or injection attempt detected.');
}
