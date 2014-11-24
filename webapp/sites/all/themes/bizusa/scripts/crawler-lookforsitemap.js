    
    function lookForSiteMapFromUrl(url, callbackFunction) {
        jQuery.get('/dev/get-html-source?url=' + url, function (strHTML) {
            var rslt = lookForSiteMapInHtml(strHTML, url);
            if ( typeof callbackFunction == 'function' ) {
                callbackFunction(rslt);
            }
        });
    }
    
    function lookForSiteMapInHtml(givenHtml, fromUrl) {
        
        // Determine the domain this HTML-source is from 
        var fromDomain = String(fromUrl).replace('http://', '').replace('https://', '').split("/");
        fromDomain = fromDomain[0];
        
        // We will want to look for anchors/links that have the text "Site Map" or "SiteMap", etc.
        var anchorsLabelsToLookFor = [
            'Site Map',
            'site map',
            'SiteMap',
            'sitemap',
            'SITEMAP'
        ];
        
        // foreach element in anchorsLabelsToLookFor
        for ( var x = 0 ; x < anchorsLabelsToLookFor.length ; x++ ) {
        
            // Build the jQuery selector that will search for links/anchors with the text anchorsLabelsToLookFor[x]
            var jQuerySelector = 'a:contains("TextSearchHere")';
            
            // Search for links/anchors with the text anchorsLabelsToLookFor[x]
            jQuerySelector = String(jQuerySelector).replace('TextSearchHere', anchorsLabelsToLookFor[x] );
            if ( jQuery(givenHtml).find(jQuerySelector).length > 0 ) {
                
                var foundAnchor = jQuery(givenHtml).find(jQuerySelector);
                var foundURL = String( foundAnchor.attr('href') );
                
                // Ignore javascript driven links as we cannot crawl/link them
                if ( foundURL.toLocaleLowerCase().indexOf('javascript:') == -1 ) {
                    
                    // if this is a relative link, convert it to a absolute path
                    if ( foundURL.substr(0, 1) == '/' ) {
                        foundURL = 'http://' + fromDomain + foundURL;
                    } else if ( foundURL.substr(0, 8) != 'https://' && foundURL.substr(0, 7) != 'http://' ) {
                        foundURL = fromUrl + '/' + foundURL;
                    }
                    
                    return foundURL;
                    
                }
                
            }
        }
        
        // If we have not returned by this line, then we have not found a site-map link on the target site
        return false;
    }