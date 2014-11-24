<?php 

    @ob_end_clean();
    while(@ob_end_clean());

    if ( !empty($_GET['target']) ) {
    
        header('X-Frame-Options: GOFORIT');

        $targURL = request_uri();
        $targURL = substr($targURL, strpos($targURL, 'target=') + 7 );
        print "
            <script>
                document.location = '$targURL';
            </script>
        ";
        
        header("Location: " . $targURL);
        
    } else {
        
        print 'Error - Missing target argument in request to this page';
        
    }
    
    exit();
    
?>