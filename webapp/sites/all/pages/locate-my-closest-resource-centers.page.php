<!--
    This code/page is used as a work-around for Akamai caching since origin
    is touched through a (Akamai) proxy, and pages are cached in full (html) .

    This "geo-strap" is used for the /request-appointment-and-closest-resource-centers page
-->
Please wait while we geo-locate your general location so we can find the closest resources to you...
<script>
    jQuery(document).ready( function () {
        // Note; getUserZipCode() is defined in globa.js at Coder Bookmark, CB-5KYKLSK-BC
        getUserZipCode( function (zipCode) {
            
            var navToURL = '/request-appointment-and-closest-resource-centers';
            navToURL += '?zip=' + zipCode;
            if ( typeof arrUrlQueries['badZip'] != 'undefined' ) {
                navToURL += '&badZip=' + arrUrlQueries['badZip'];
            }
            
            document.location = navToURL;
        });
    });

</script>
