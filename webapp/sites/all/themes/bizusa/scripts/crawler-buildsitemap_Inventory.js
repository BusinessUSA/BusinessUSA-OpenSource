
    function beginBuildOfHtmlSiteMapFromUrl(url, domContainerElement, crawlMaxLevelsDeep, currentLevel) {
        
        // Default the currentLevel parameter to 0
        if ( typeof currentLevel == 'undefined' ) {
            currentLevel = 0;
        }

        // This is a global JavaScript variable, when set to TRUE, terminate this functionality
        if ( typeof terminateCrawl == 'undefined' ) {
            terminateCrawl = false;
        }
        
        // We shall track what URLs we have called in the global urlsCrawled global variable (so we don't crawl the same URL more than once)
        if ( typeof urlsCrawled == 'undefined' ) {
            urlsCrawled = [];
        }
        
        // Check if this given url has been crawled before (so we don't crawl the same URL more than once)
        if ( urlsCrawled.indexOf(url) != -1 ) {
            return;
        }
        
        // Pull the HTML-source for the target url, and parse for all internal-links on that page 
        consoleLog('SiteMap Generator: Beginning to crawl ' + url);
        urlsCrawled.push(url);
        getAllInternalLinksInURL(url, function (linksFound) {
            
            // DevNote: linksFound is an ARRAY, NOT a javascript OBJECT in this context
            consoleLog('SiteMap Generator: Got HTML-source for page ' + url + ' this page has ' + linksFound.length + ' internal-links');
            
            if ( terminateCrawl == true ) {
                return;
            }
            
            // Determine the domain this HTML-source is from 
            var fromDomain = String(url).replace('http://', '').replace('https://', '').split("/");
            fromDomain = fromDomain[0];
            
            // Build a structured array (javascript object) mapping a sitemap based on url-directories found on all internal-links on this page
            buildSiteMap(linksFound); // Note: this function ADDS onto (does not replace) the global siteMapPaths JavsScript variable
            
            // Build human-readable HTML (a user-interface) list (<ul>s) based on the global siteMapPaths variable, and display this UI in the domContainerElement container (on the DOM)
            var siteMapHtml = _buildHtmlSiteMap(siteMapPaths, 0, '', fromDomain);
            jQuery(domContainerElement).html( siteMapHtml['html'] );
            
            // We shall now continue crawling the target site unless currentLevel > crawlMaxLevelsDeep
            if ( currentLevel < crawlMaxLevelsDeep ) {
                for ( var x = 0 ; x < linksFound.length ; x++ ) {
                    beginBuildOfHtmlSiteMapFromUrl( linksFound[x], domContainerElement, crawlMaxLevelsDeep, currentLevel + 1 );
                }
            }
            
        });
    }

    function _buildHtmlSiteMap(objSiteMap, level, path, urlDomain) {
        
        var html = '';
        
        // Sort the URL-directories found on this path (alphabetically)
        var keys = [];
        for ( var key in objSiteMap ) {
            keys.push(key);
        }
        keys = keys.sort();
        
        // For each URL-directory found in this path
        for ( var x = 0 ; x < keys.length ; x++ ) {
            var key = keys[x];
            
            // Recursively build a, html-sitemap  from this child-directory
            var child_htmlSitemap = _buildHtmlSiteMap(objSiteMap[key], level + 1, path + '/' + key, urlDomain);
            
           // if ( key != '' ) {
            //    html += '<li class="generatedsitemap-li generatedsitemap-li-level-' + level + '">';
            //    html += '<span class="generatedsitemap-item generatedsitemap-item-level-' + level + '">';
           //     html += '<a target="_blank" href="http://' + String(urlDomain + '/' + path + '/' + key).replace('//', '/') + '">';
           //     html += key
           //     html += '</a>';
           //     if ( child_htmlSitemap['count'] > 0 ) {
          //          html += ' <span class="generatedsitemap-childcount">(' + child_htmlSitemap['count'] + ')</span>';
          //      }
          //      html += '</span>';
          //      html += child_htmlSitemap['html'];
          //      html += '</li>';
          //  }

          if (key != '')
            {
                html += '<table>';
                html += '<thead>';
                html += '<tr>';
                html += '<th>Content Type</th>';
                html += '<th>Pages/Number</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                html += '<tr>';
                html += '<td>';
                html += '<a target="_blank" href="http://' + String(urlDomain + '/' + path + '/' + key).replace('//', '/') + '">';
                html += key;
                html += '</a>';
                html +='</td>';
                html += '<td>';
                html += child_htmlSitemap['count'];
                html +='</td>';
                html +=  '</tr>';
                html += '</tbody>';
                html += '</table>';
            }
        }

        alert ('hello');
        
       // html += '</ul>';

        // Bug killer - don't return any HTML if all there is to return is an empty UL container
        html = String(html).replace('<ul></ul>', '');
        
        return {
            'count': keys.length,
            'html': html
        };
    }

    function buildSiteMap(arrLinks) {
        
        if ( typeof siteMapPaths == 'undefined' ) {
            siteMapPaths = {};
        }
        
        for ( var x = 0 ; x < arrLinks.length ; x++ ) {
            var thisURL = String( arrLinks[x] );
            var pathDirs = thisURL.replace('https://', '').replace('http://', '').split('/');
            pathDirs.shift(); // shift off the domain name
            siteMapPaths = _buildSiteMap_mergeInPath(siteMapPaths, pathDirs);
        }
        
        return siteMapPaths;
    }

    function _buildSiteMap_mergeInPath(objSiteMap, arrDirs) {
        
        var ret = objSiteMap;
        var dirs = arrDirs;
        var immediateDir = dirs[0];
        
        if ( typeof ret[immediateDir] == 'undefined' ) {
            ret[immediateDir] = {};
        }
        
        dirs.shift();
        if ( dirs.length > 0 ) {
            ret[immediateDir] = _buildSiteMap_mergeInPath(ret[immediateDir], dirs);
        }
        
        return ret;
    }

    function getAllInternalLinksInURL(url, callbackFunct) {
        jQuery.get('/dev/get-html-source?url=' + url, function (strHTML) {
            var linksFound = getAllInternalLinksInHTML(strHTML, url);
            if ( typeof callbackFunct == 'function' ) {
                callbackFunct(linksFound);
            }
        });
    }

    function getAllInternalLinksInHTML(htmlString, fromURL) {

        var ret = [];

        // Determine the domain this HTML-source is from 
        var fromDomain = String(fromURL).replace('http://', '').replace('https://', '').split("/");
        fromDomain = fromDomain[0];
        
        // Get all jQuery anchor objects from the given HTML-source
        linkList = jQuery(htmlString).find('a').filter( function () {
            
            var jqThis = jQuery(this);
            
            // Filter out anchors which do not have href properties
            if ( typeof this.href == 'undefined' || this.href === '' ) {
                return false;
            }
            
            // Filter out mail-to links
            if ( Boolean(this.href.match(/^mailto\:/)) ) {
                return false;
            }
            
            // Filter out external links
            if ( String(jqThis.attr('href')).indexOf('http') != -1 && String(this.hostname).replace('http://', '').replace('https://', '') != fromDomain ) {
                return false;
            }
            
            // Filter pointers to anchors (URLs that start with hashes)
            if ( jqThis.attr('href').substr(0,1) == '#' ) {
                return false;
            }
            
            return true;
        });
        
        // Run through the found anchors, create an array of full-path URLs
        for ( var x = 0 ; x < linkList.length ; x++ ) {
            var thisHref = String( linkList.eq(x).attr('href') );
            if ( thisHref.indexOf('http') == -1 ) {
                thisHref = 'http://' + fromDomain + thisHref;
            }
            ret.push( thisHref );
        }
        
        return ret;
    }