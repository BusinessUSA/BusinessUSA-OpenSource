<?php 

    if ( empty($_GET['ccontent']) ) {
        exit('Error - Missing ccontent argument in request');
    }
    
    // Get target URL
    $targURL = request_uri();
    $targURL = substr( $targURL, strpos($targURL, 'ccontent=') + 9 );
    
    // Verify this site is not going to reject frames
    $targetsHeaders = call_user_func_cache(1814400, 'get_headers', $targURL, 1);
    if ( !empty($targetsHeaders['X-Frame-Options']) && stripos($targetsHeaders['X-Frame-Options'], 'SAMEORIGIN') !== false ) {
        header('Location: ' . $targURL);
        print "<script> document.location = '$targURL'; </script>";
        exit();
    }
?>
    
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" version="XHTML+RDFa 1.0" dir="ltr" xmlns:content="http://purl.org/rss/1.0/modules/content/"  xmlns:dc="http://purl.org/dc/terms/"  xmlns:foaf="http://xmlns.com/foaf/0.1/"  xmlns:og="http://ogp.me/ns#"  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"  xmlns:sioc="http://rdfs.org/sioc/ns#"  xmlns:sioct="http://rdfs.org/sioc/types#"  xmlns:skos="http://www.w3.org/2004/02/skos/core#" xmlns:xsd="http://www.w3.org/2001/XMLSchema#">

    <frameset resizable="no" rows="50px,*" border="0" frameborder="0">
        <frame name="top" src="/ext-top?subject=<?php print htmlspecialchars($_GET['subject']); ?>&closeTo=<?php print $targURL; ?>" scrolling="no" style="border-bottom: 1px solid black;"></frame>
        <frame name="mainnav" id="mainnav" src="/goto?target=<?php print $targURL; ?>"></frame>
    </frameset>

    <!-- Google Analytics for this page  -->
    <script type="text/javascript" src="http://business.usa.gov/sites/all/themes/bususa/js/federated-analytics.js"></script>
    <script>
          var _gaq = _gaq || [];_gaq.push(["_setAccount", "UA-19362636-19"]);_gaq.push(["_trackPageview"]);(function() {var ga = document.createElement("script");ga.type = "text/javascript";ga.async = true;ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(ga, s);})();
    </script>
    <script>
        var _gaq = _gaq || [];_gaq.push(["_setAccount", "UA-17367410-37"]);_gaq.push(["_trackPageview"]);(function() {var ga = document.createElement("script");ga.type = "text/javascript";ga.async = true;ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(ga, s);})();
    </script>
    
</html>