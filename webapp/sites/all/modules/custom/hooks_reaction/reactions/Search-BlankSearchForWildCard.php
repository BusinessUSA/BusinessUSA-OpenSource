<?php

    if ( trim($_SERVER['REQUEST_URI'], '/') === 'search/site' ) {
        @ob_end_clean();
        while (@ob_end_clean());
        print "<script> document.location = '/search/site/%2A'; </script>";
        exit();
    }
    else if ( trim($_SERVER['REQUEST_URI'], '/') === 'events' ) {
        @ob_end_clean();
        while (@ob_end_clean());
        print "<script> document.location = '/events-search'; </script>";
        exit();
    }
