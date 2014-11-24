<?php
    
    @ob_end_clean();
    @ob_end_clean();
    @ob_end_clean();
    
    if ( empty($_GET['hid']) ) {
        exit("Error - Missing hex-id (hid) parameter");
    }
    
    if ( hexdec($_GET['hid']) === 0 ) {
        exit("Error - Invalid hex-id (hid)");
    }
    
    getQuickLinkTarget($_GET['hid'], true); // Note: this function is defined in QuickLinks.php
    
?>