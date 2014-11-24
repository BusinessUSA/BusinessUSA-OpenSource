/************************************************************************
 ***   SCRIPTS AND DECLARATIONS TO FIRE IMMEDIATELY / BEFORE $(DOCUMENT).READY()   ****
 ************************************************************************/

  // Bug killer
$ = jQuery;

/**  void consoleLog(inptMsg)
 *  Executes console.log with given parameter if console.log is defined
 */
function consoleLog(inptMsg) {
  if (typeof console != 'undefined') {
    console.log(inptMsg);
  }
}

/**  Special javascript-global array/object variables containing the parsed URL query
 *
 *  array variable arrUrlQueries - contains the URL query (as seen in the address bar) prased
 *  array variable objUrlQueries - contains the URL query (as seen in the address bar) prased
 */
arrUrlQueries = String(document.location.search).replace('?', '').split('&');
objUrlQueries = {};
for (var x = 0; x < arrUrlQueries.length; x++) {
  thisQueryParams = String(arrUrlQueries[x]).split('=');
  if (thisQueryParams.length == 0) {
    // nothing to add to objUrlQueries
  } else if (thisQueryParams.length == 1) {
    objUrlQueries[thisQueryParams[0]] = thisQueryParams[0];
  } else if (thisQueryParams.length > 1) {
    objUrlQueries[thisQueryParams[0]] = thisQueryParams[1];
  }
}

/**  void createCookie(name,value,days)
 *  Saves a [new] cookie
 */
function createCookie(name, value, days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    var expires = "; expires=" + date.toGMTString();
  } else {
    var expires = "";
  }
  document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}
//Function to ready query string e.g.  var zipcode = getUrlVars()["zip"];
function getUrlVars() {
  var vars = [], hash;
  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
  for (var i = 0; i < hashes.length; i++) {
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}


/**  string createCookie(name,value,days)
 *  Retrieves a cookie or null when not found
 */
function readCookie(name) {
  var nameEQ = escape(name) + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return unescape(c.substring(nameEQ.length, c.length));
  }
  return null;
}

/**  long getCurrentUnixEpochTime()
 *
 *  Returns the current Unix Epoch time. This is the same thing the PHP function time() should return.
 *  For more information on Unix Epoch Time, please refer to https://en.wikipedia.org/wiki/Unix_time
 */
function getCurrentUnixEpochTime() {
  return Math.round((new Date()).getTime() / 1000);
}

/**  void redirect(url)
 *
 *  DO NOT REMOVE THIS FUNCTION! This function is used by pages ripped from export.gov
 *  Call may be made to this function from pages ripped from export.gov
 */
function redirect(url) { /* Coder Bookmark: CB-L1KSLZE-BC */
    document.location = String(url).replace('http://export.gov/', '/export-portal').replace('https://export.gov/', '/export-portal');
}

/**  void getUserZipCode(callback)
 *
 *  Try to auto detect the user's ZipCode, once obtained, triggers the callback function with the obtained ZipCode
 */
function getUserZipCode(callback) { /* Coder Bookmark: CB-5KYKLSK-BC */

  // Store the callback in a variable
  if (typeof getUserZipCode_callbacks == 'undefined') {
    getUserZipCode_callbacks = [];
  }
  getUserZipCode_callbacks.push(callback);

  // Declare callback function browserLatLongReturned()
  browserLatLongReturned = function (geoData) {

    autoLat = geoData['geoplugin_latitude'];
    autoLong = geoData['geoplugin_longitude'];

    consoleLog('User lat/long = ' + autoLat + ',' + autoLong);
    consoleLog(geoData);
    createCookie('autoLat', autoLat, 1);
    createCookie('autoLong', autoLong, 1);
    consoleLog('This information is now saved as cookies in autoLat and autoLong /* Coder Bookmark: CB-3T1XQA4-BC */');

    consoleLog('Getting zip-code based on this information...');
    // Get ZCData and trigger browserZipCodeReturned() as a callback
    if (String(document.location.protocol) !== 'https:' && String(document.location.protocol) !== 'https') {
      jQuery.getScript('http://www.geoplugin.net/extras/postalcode.gp?lat=' + autoLat + '&long=' + autoLong + '&format=json&jsoncallback=browserZipCodeReturned');
    } else {
      // geoplugin.net cannot be loaded over HTTPS, so assume the location of the White House
      browserZipCodeReturned({
        geoplugin_postCode: 20500,
        geoplugin_latitude: autoLat,
        geoplugin_longitude: autoLong,
      });
    }
  };

  // Declare callback function browserZipCodeReturned()
  browserZipCodeReturned = function (zcData) {

    autoZip = zcData['geoplugin_postCode'];

    autoLat = zcData['geoplugin_latitude'];
    autoLong = zcData['geoplugin_longitude'];
    consoleLog('User lat/long = ' + autoLat + ',' + autoLong + ' Coder Bookmark: CB-H3H2RJW-BC');

    consoleLog('Got the users zipcode information. It is ' + autoZip);
    createCookie('autoZip', autoZip, 1);
    for (var x = 0; x < getUserZipCode_callbacks.length; x++) {
      var callbackToCall = getUserZipCode_callbacks[x];
      if (typeof callbackToCall == "function") {
        callbackToCall(autoZip, autoLat, autoLong);
      }
    }
  };

  // Get the user's zip-code location based on either a stored cookie, or by geoLocation
  if (readCookie('autoZip') != null && readCookie('autoZip') != 'null') {
    consoleLog('The user ZC is stored in a cookie, using that instead of geo-location.');
    browserZipCodeReturned({
      'geoplugin_postCode': readCookie('autoZip'),
      'geoplugin_latitude': readCookie('autoLat'),
      'geoplugin_longitude': readCookie('autoLong')
    });
  } else if (typeof autoZip != 'undefined' && autoZip != false) {
    consoleLog('While it seems cookies are disabeled, the ZC has been previouly obtained and cached in a global javaScript variable.');
    browserZipCodeReturned({
      'geoplugin_postCode': autoZip,
      'geoplugin_latitude': autoLat,
      'geoplugin_longitude': autoLong
    });
  } else {
    // Use geoLocation - Get GeoData and trigger browserLatLongReturned() as a callback
    if (String(document.location.protocol) !== 'https:' && String(document.location.protocol) !== 'https') {
      jQuery.getScript('http://www.geoplugin.net/json.gp?jsoncallback=browserLatLongReturned');
    } else {
      // geoplugin.net cannot be loaded over HTTPS, so assume the location of the White House
      browserLatLongReturned({
        geoplugin_latitude: 38.8975657,
        geoplugin_longitude: -77.036484
      });
    }
  }
}

//Validate email address
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    // var pattern = new RegExp('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$');
    return pattern.test(emailAddress);
}

// For all any and all input.auto-fill-zip-code elements, geoLocate the user's zip-code, and place the value into the input
var autoZip = false;
function autoFillZipCode() {
  if (jQuery('input.auto-fill-zip-code').not('.auto-fill-zip-code-applied').length > 0) {
    consoleLog('Elements with the auto-fill-zip-code have been found, getting the users zip-code...');
    getUserZipCode(function (zipCode) {
      jQuery('.auto-fill-zip-code').not('.auto-fill-zip-code-applied').each(function () {
        var jqThis = jQuery(this);
        jqThis.val(zipCode); // Set the value into the textbox
        jqThis.change(); // Force-fire the change event, which may not have triggered with the jqThis.val(~)
        jqThis.addClass('auto-fill-zip-code-applied'); // Add a class denoting that we have [now/previously] applied this action
      });
    });
  }
};
jQuery(document).ready(function () {
  autoFillZipCode();
});

/**
 * XMLHTTPRequest phpFunction(string phpFunctionName, array parameters, object options, function callback)
 *
 * A JavaScript tunnel-function to call and return the target phpFunctionName.
 * This function is dependant on the <Site>/sys/ajax/php-function-access-tunnel page.
 * Obviously, for security reasons, the server will allow ONLY CERTAIN PHP-function names with this usage.
 *
 * Example parameter given to callback of: phpFunction('getLatLongFromZipCode', [20171]);
 * {
        time: 0.00089192390441895,
        return: {
            lat: "38.931479",
            lng: "-77.40085",
            state: "VA",
            city: "Herndon"
        },
        print: ""
    }
 */
function phpFunction(phpFunctionName, parameters, options, callback) {

  // Handel parameter skipping
  if (typeof options == 'function' && typeof callback == 'undefined') {
    callback = options;
    options = {};
  }

  var defaultOptions = {
    'bypassAkamaiCache': false,
    'cacheReturns': true,
    'method': 'get',
    'return': 'return',
  };
  options = jQuery.extend(defaultOptions, options);

  options['method'] = String(options['method']).toLowerCase()

  options['return'] = String(options['return']);
  options['return'] = jQuery.trim(options['return']);
  switch (options['return']) {
    case '':
    case 'false':
    case 'null':
      options['return'] = '';
      break;
    default:
    // leave as is
  }

  if (typeof callback != 'function') {
    callback = function (r) { /* just a blank dummy function */
    };
  }

  if (typeof parameters == 'undefined') {
    parameters = [];
  } else if (typeof parameters == 'string' || typeof parameters == 'number') {
    parameters = [parameters];
  }
  parameters = escape(JSON.stringify(parameters));

  var url = '/sys/ajax/php-function-access-tunnel';
  url += '?function=' + phpFunctionName;
  if (options['bypassAkamaiCache'] == true) {
    url += '&bypassAkamaiCache=' + Math.floor((Math.random() * 100000));
  }
  if (options['method'] != 'post') {
    url += '&params=' + parameters;
  }

  // If allowed, pull from cache
  var retCache = false;
  if (options['cacheReturns'] == true) {
    retCache = _phpFunction_cacheFetch(phpFunctionName, parameters);
  }

  return _phpFunction_tunnel(url, parameters, options, retCache, function (serverReturn, pulledFromCache) {

    // Cache the return from the server
    if (pulledFromCache == false) {
      _phpFunction_cacheStore(phpFunctionName, parameters, serverReturn);
    }

    // Fire the callback function
    var toReturn = serverReturn;
    if (options['return'] != '') {
      toReturn = toReturn[ options['return'] ];
    }
    callback(toReturn);

  });

}

function _phpFunction_cacheStore(phpFunctionName, parameters, serverReturn) {

  if (typeof phpFunctionCache == 'undefined') {
    phpFunctionCache = [];
  }

  phpFunctionCache.push({
    'request': {
      'phpFunctionName': phpFunctionName,
      'parameters': parameters
    },
    'serverReturn': serverReturn
  });

}

function _phpFunction_cacheFetch(phpFunctionName, parameters) {

  if (typeof phpFunctionCache == 'undefined') {
    return false;
  }

  var thisRequest = JSON.stringify({
    'phpFunctionName': phpFunctionName,
    'parameters': parameters
  });

  for (var x = 0; x < phpFunctionCache.length; x++) {

    if (thisRequest == JSON.stringify(phpFunctionCache[x]['request'])) {
      return phpFunctionCache[x]['serverReturn'];
    }

  }

  return false;

}

function _phpFunction_tunnel(url, parameters, options, cacheReturn, internalCallback) {

  if (cacheReturn !== false) {
    internalCallback(cacheReturn, true);
    return {};
  }

  if (options['method'] != 'post') {
    return jQuery.get(url, function (data) {
      internalCallback(data, false);
    });
  } else {
    return jQuery.post(url, {'params': parameters}, function (data) {
      internalCallback(data, false);
    });
  }

}

/**  void drawAllMarkersOnGoogleMap()
 *
 * Used to draw markers on a GoogleMap, this function search the dom for
 * anything that has a class of googlemap-markme, and look for makring
 * information inside it.
 */
function drawAllMarkersOnGoogleMap() {

  jQuery('.googlemap-markme').each(function () {
    var jqThis = jQuery(this);
    var lat = jqThis.find('.googlemap-markme-lat').text();
    var lng = jqThis.find('.googlemap-markme-lng').text();
    var title = jqThis.find('.googlemap-markme-title').text();
    drawMarkerOnGoogleMap(map, lat, lng, title);
  });
}

/**  void drawMarkerOnGoogleMap(googleMap, latitude, longitude, markerTitle)
 *
 *  Used to draw a marker on a GoogleMaps
 */
function drawMarkerOnGoogleMap(googleMap, latitude, longitude, markerTitle) {

  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(latitude, longitude),
    map: googleMap,
    title: markerTitle
  });

  google.maps.event.addListener(marker, 'click', function () {
    infowindow.setContent(markerTitle);
    infowindow.open(map, this);
  });
}

/*****************************
 ***   EXTERNAL NAV BAR / EXTERNAL LINK FRAMES IMPLEMENTATION   ****
 ****************************/
function frameExternalLinks(context, settings) {

  var noFrameForDomains = [
    'help.business.usa.gov',
    'help.businessusa.gov',
    'twitter',
    'google',
    'facebook',
    'yahoo',
    'my.usa.gov',
    'sba.gov',
    'CBP.gov','cbp.gov',
    'iowaeconomicdevelopment.com',
    'state.ia.us',
    'amazon.co.jp',
    'fas.usda.gov',
    'iowa.gov',
    'cdc.gov',
    'bt.cdc.gov',
    'osha.gov',
    'fema.gov',
  ];
  jQuery('a', context).not('.no-ext-frame').each(function () {
    var jqThis = jQuery(this);
    if (jqThis.attr('href')) {
      if ((this.hostname != location.hostname) && (this.protocol == 'http:' || this.protocol == 'https:')) {

        var doFrameThisLink = true;
        for (var x = 0; x < noFrameForDomains.length; x++) {
          if (this.hostname.indexOf(noFrameForDomains[x]) != -1) {
            doFrameThisLink = false;
          }
        }

        var newHref = !doFrameThisLink ? '/goto?' : '/external-site?';
        if (typeof jqThis.parent().parent().attr('class') != 'undefined' && jqThis.parent().parent().attr('class') == 'views-field views-field-field-news-link') {
          newHref += "subject=biznews&";
        } else if (typeof jqThis.parent().parent().parent().parent().parent().attr('class') != 'undefined' && String(jqThis.parent().parent().parent().parent().parent().attr('class')).indexOf("view-export-news view-id-export_news") != -1) {
          newHref += "subject=exportnews&";
        }
        else if(jqThis.parents('.view-rural-export-initiatives-news').length) {
          newHref += "subject=ruralexportnews&";
        }
        newHref += !doFrameThisLink ? 'target=' : 'ccontent=';
        newHref += jqThis.attr('href');
        jqThis.attr('href', newHref);
        jqThis.attr('target', '_blank');
        jqThis.addClass('ext-link');
      }
    }
  });
}
Drupal.behaviors.externalLinks = {attach: frameExternalLinks};

/*****************************
 ***   SYNCH-HEIGHT CLASS IMPLEMENTATION  ****
 ****************************/
function resynchHeight() {

  // Make sure the body class exists (may not when called before DOM is fully loaded)
  if (typeof jQuery('body').attr('class') == 'undefined') {
    return;
  }

  // Remove synched-groups flags from the body's class tag
  jQuery(jQuery('body').attr('class').split(' ')).each(function () {
    if (String(this).indexOf('as-synched-group-') != -1) {
      jQuery('body').removeClass(String(this));
    }
  });

  // Remove height set to elements
  jQuery('.synch-height').css('height', '');
  // Synch heiths across all synch-height-groups
  var elementsToSynch = jQuery('.synch-height');
  if (elementsToSynch.length > 0) {
    elementsToSynch.each(function () {
      var jqThis = jQuery(this);
      if (jqThis.attr('synchheightgroup') != undefined) {
        var synchGroup = jqThis.attr('synchheightgroup');
        if (jQuery('body').hasClass('has-synched-group-' + synchGroup) == false) {
          var elementsInGroupToSynch = jQuery('.synch-height[synchheightgroup="' + synchGroup + '"]');
          greatestHeight = 0;
          if (jQuery(window).width() < 768) {
            elementsInGroupToSynch = elementsInGroupToSynch.not('.synch-height-butnotinmobile');
          }
          elementsInGroupToSynch.each(function () {
            if (greatestHeight < jQuery(this).height()) {
              greatestHeight = jQuery(this).height();
            }
          });
          elementsInGroupToSynch.css('height', greatestHeight);
          elementsInGroupToSynch.attr('note', 'The height of this element has been set by the synch-height implementation. Coder Bookmark: CB-WJ4HX4P-BC');
          elementsInGroupToSynch.addClass('height-synched');
          jQuery('body').addClass('has-synched-group-' + synchGroup);
        }
      }
    });
  }
}
jQuery(document).ready(function () {
  setTimeout('resynchHeight()', 100);
  setInterval(function () { /* Coder Bookmark: CB-520RQ16-BC */
    if (typeof synchheightdamon_lastwindowwidth == 'undefined') {
      synchheightdamon_lastwindowwidth = 0;
    }
    if (synchheightdamon_lastwindowwidth == jQuery(window).width()) {
      return;
    }
    consoleLog('Window width has changed, now firing resynchHeight(), Coder Bookmark: CB-DCDUX5M-BC ');
    resynchHeight();
    synchheightdamon_lastwindowwidth = jQuery(window).width();
  }, 1500);
});

//Events viewmore/View less functionality added as per Requirement on 05/02/14
function expanddesc(param) {
  $(param).parent().hide();

  $(param).parent().siblings().show();

}

// For reference by other programs
state_abbr = {
  'AL': 'Alabama',
  'AK': 'Alaska',
  'AS': 'America Samoa',
  'AZ': 'Arizona',
  'AR': 'Arkansas',
  'CA': 'California',
  'CO': 'Colorado',
  'CT': 'Connecticut',
  'DE': 'Delaware',
  'DC': 'District of Columbia',
  'FM': 'Micronesia1',
  'FL': 'Florida',
  'GA': 'Georgia',
  'GU': 'Guam',
  'HI': 'Hawaii',
  'ID': 'Idaho',
  'IL': 'Illinois',
  'IN': 'Indiana',
  'IA': 'Iowa',
  'KS': 'Kansas',
  'KY': 'Kentucky',
  'LA': 'Louisiana',
  'ME': 'Maine',
  'MH': 'Islands1',
  'MD': 'Maryland',
  'MA': 'Massachusetts',
  'MI': 'Michigan',
  'MN': 'Minnesota',
  'MS': 'Mississippi',
  'MO': 'Missouri',
  'MT': 'Montana',
  'NE': 'Nebraska',
  'NV': 'Nevada',
  'NH': 'New Hampshire',
  'NJ': 'New Jersey',
  'NM': 'New Mexico',
  'NY': 'New York',
  'NC': 'North Carolina',
  'ND': 'North Dakota',
  'OH': 'Ohio',
  'OK': 'Oklahoma',
  'OR': 'Oregon',
  'PW': 'Palau',
  'PA': 'Pennsylvania',
  'PR': 'Puerto Rico',
  'RI': 'Rhode Island',
  'SC': 'South Carolina',
  'SD': 'South Dakota',
  'TN': 'Tennessee',
  'TX': 'Texas',
  'UT': 'Utah',
  'VT': 'Vermont',
  'VI': 'Virgin Island',
  'VA': 'Virginia',
  'WA': 'Washington',
  'WV': 'West Virginia',
  'WI': 'Wisconsin',
  'WY': 'Wyoming'
};

function createFlyoutNotification() {
  // Remove any existing flyers.
  $('.recommended-flyout-container').remove();
  var path = window.location.pathname;
  var anchor = window.location.hash;
  if (anchor) {
    path += anchor;
  }
  conditions = {
    "entityCondition": [
      [ "entity_type", "node" ],
      [ "bundle", "recommended_flyout" ]
    ],
    "fieldCondition": [
      [ "field_display_page_path", "value", [path], "=" ]
    ]
  }
  if ( typeof entity_field_query_json != 'undefined' ) {
      entity_field_query_json(conditions, true).done(function(data) {
        if (data == 'No entities loaded.') {
          return;
        }
        $.each(data, function(key, value) {
          $.each(value, function(nid, node) {
            var now = new Date();
            var publishDate = (node.field_date_published.und) ? node.field_date_published.und[0].value : null;
            var unpublishDate = (node.field_unpublishing_date.und) ? node.field_unpublishing_date.und[0].value : null;
            publishDate = (publishDate) ? publishDate.substr(0, 10) : publishDate;
            unpublishDate = (unpublishDate) ? unpublishDate.substr(0, 10) : unpublishDate;
            if (publishDate) {
              publishDate = new Date(publishDate);
              if (publishDate > now) {
                // Publish date is in the future, don't do anything for now.
                return;
              }
            }
            if (unpublishDate) {
              unpublishDate = new Date(unpublishDate);
              if (unpublishDate < now) {
                // Unpublish date is in the past, don't do anything.
                return;
              }
            }

            // Unfortunately we have to render the teaser view to extract the full
            // image path because the node only provides the uri.
            entity_render_view('node', node.nid, 'teaser').done(function(data) {
              // We completed validation - extract the title and snippet
              // from the node.
              var title = node.title;
              var snippet = (node.field_snippet.und) ? node.field_snippet.und[0].value : '';
              var landingPage = node.field_landing_page_path.und[0].value;
              var img = $(data).find('img').attr('src');
              if (typeof img != 'undefined') {
                var html = '<div class="recommended-flyout-container">' +
                  '<div class="recommended-flyout-wrap" style="width: 360px;">' +
                  '<div class="recommended-flyout">' +
                  '<a href="' + landingPage +'" class="recommended-flyout-link" target="_blank">' +
                  '<img src="' + img + '" class="recommended-flyout-content-image" width="70" height="70">' +
                  '<h6 class="recommended-flyout-heading">' + title + '</h6>' +
                  '<h4 class="recommended-flyout-snippet">' + snippet + '</h4>' +
                  '</a>' +
                  '<span class="recommended-flyout-close">✖</span>' +
                  '</div>' +
                  '</div>' +
                  '</div>';
              }
              else {
                var html = '<div class="recommended-flyout-container">' +
                  '<div class="recommended-flyout-wrap" style="width: 360px;">' +
                  '<div class="recommended-flyout">' +
                  '<a href="' + landingPage +'" class="recommended-flyout-link" target="_blank">' +
                  '<h6 class="recommended-flyout-heading">' + title + '</h6>' +
                  '<h4 class="recommended-flyout-snippet">' + snippet + '</h4>' +
                  '</a>' +
                  '<span class="recommended-flyout-close">✖</span>' +
                  '</div>' +
                  '</div>' +
                  '</div>';
              }

              $('#contentArea').append(html);

              $('.recommended-flyout-wrap').hide();

              setTimeout(function () {
                // Show the flyer after 10 seconds.
                var flyoutWrapper = $('.recommended-flyout-wrap');
                if (!flyoutWrapper.is(':visible')) {
                  flyoutWrapper.show("slide", { direction: "right" }, 1000);
                }
              }, 10000);
            });

            // We just need the first value, break out of the loop.
            return false;
          });
        });
      });
  }
}

/************************************************************************
 ***   SCRIPTS TO FIRE AFTER/ON $(DOCUMENT).READY()   ****
 ************************************************************************/
(function ($) {
  $(document).ready(function () {


      function detectIE() {
          var ua = window.navigator.userAgent;
          var msie = ua.indexOf('MSIE ');
          var trident = ua.indexOf('Trident/');

          if (msie > 0) {
              // IE 10 or older => return version number
              return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
          }

          if (trident > 0) {
              // IE 11 (or newer) => return version number
              var rv = ua.indexOf('rv:');
              return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
          }

          // other browser
          return 1000;
      }
      if (detectIE() <= 8 )
      {
        window.location="/notsupported_browser";
      }





    setTimeout(function () {
      $("#wiztag-splash-next-button").focus();
    }, 1);
    
    // noscript tags tend to show up in IE8, hide them
    jQuery('noscript').hide();
    
    //On page scroll switch menus
    var mainNav = $('#mainNavigation'),
      scrollNav = $('#scrollNavigation');
    scrollNav.hide();
    $(window).scroll(function () {
      $.SmartMenus.hideAll();
      mainNav.css({display: $(this).scrollTop() < 75 ? "block" : "none"});
      scrollNav.css({display: $(this).scrollTop() >= 75 ? "block" : "none"});
    });

    //Update the Ownership menu to be non-clickable
    if ($('.menu .ownership a:eq(0)').length > 0) {
      $('.menu .ownership a:eq(0)').attr('href', '#');
    }

    //script to have wizard question options in 2 columns
    if ($('body').hasClass('page-contains-wizard')) {

      $(".wiztag-label1 ~ div.widget-type-checkbox").wrapAll('<div class="has-two-columns">');
      $(".wiztag-label_a ~ div.widget-type-checkbox").wrapAll('<div class="has-two-columns">');
    }

    /* Implement the YAPager_hook_pagechange - when the user clicks to move to another page in the 
    pager, show/hide the "Top 5" prepended text from the wizard's title section as nessesary */
    if ( typeof yaPager_hooks_pagechange == 'undefined' ) {
        yaPager_hooks_pagechange = [];
    }
    yaPager_hooks_pagechange.push( function (yaPager, pageId) {
        var wizardTop5TitleArea = yaPager.parents('.wizard-result-section').find('.wizard-result-section-title .top5');
        if ( pageId == 0 ) {
            wizardTop5TitleArea.show();
        } else {
            wizardTop5TitleArea.hide();
        }
    });
    
    //Blog specific script to be executed
    /*
    if ($('body').hasClass('page-blog')) {
      if ($('.BusinessUSA').length > 0) {
        var script = $('.BusinessUSA').attr('class').replace(/\s+/g, '-').toLowerCase();

        script = script.replace('from-', '');
        script = script.replace('-blog', '');
        $('.BusinessUSA').attr('class', script);
        $('.businessusa').before('<div class="img-businessusa"></div>');

        var current;
        $(".img-businessusa, .businessusa").each(function () {
          var $this = $(this);
          if ($this.hasClass('img-businessusa')) {
            current = $('<div></div>').insertBefore(this);
          }
          current.append(this);
        });

      }
      if ($('.Whitehouse').length > 0) {
        var script = $('.Whitehouse').attr('class').replace(/\s+/g, '-').toLowerCase();

        script = script.replace('from-', '');
        script = script.replace('-blog', '');
        $('.Whitehouse').attr('class', script);
        $('.whitehouse').before('<div class="img-whitehouse"></div>');

        var current;
        $(".img-whitehouse, .whitehouse").each(function () {
          var $this = $(this);
          if ($this.hasClass('img-whitehouse')) {
            current = $('<div></div>').insertBefore(this);
          }
          current.append(this);
        });

      }
      if ($('.Administration').length > 0) {
        var script = $('.Administration').attr('class').replace(/\s+/g, '').toLowerCase()
        //script = $('.Administration').attr('class').replace(/\s+/g, '-').toLowerCase();

        script = script.replace('from', '');
        script = script.replace('blog', '');
        $('.Administration').attr('class', script);
        $('.smallbusinessadministration').before('<div class="img-smallbusinessadministration"></div>');

        var current;
        $(".img-smallbusinessadministration, .smallbusinessadministration").each(function () {
          var $this = $(this);
          if ($this.hasClass('img-smallbusinessadministration')) {
            current = $('<div></div>').insertBefore(this);
          }
          current.append(this);
        });

      }

      if ($('.Commerce').length > 0) {
        var script = $('.Commerce').attr('class').replace(/\s+/g, '').toLowerCase();

        script = script.replace('from', '');
        script = script.replace('blog', '');
        $('.Commerce').attr('class', script);
        $('.departmentofcommerce').before('<div class="img-departmentofcommerce"></div>');

        var current;
        $(".img-departmentofcommerce, .departmentofcommerce").each(function () {
          var $this = $(this);
          if ($this.hasClass('img-departmentofcommerce')) {
            current = $('<div></div>').insertBefore(this);
          }
          current.append(this);
        });

      }


      if ($('.view-content').children().not('blog-item-container')) {
        $(".view-content").children().addClass("blog-item-container");
      }

      if ($('#block-views-blog-block_1').length > 0) {
        $('#block-views-blog-block_1').find('.view-content').toggle();
        $('.views-widget').toggle();
      }


      if ($('#block-views-blog-block_1').length > 0) {
        $('#block-views-blog-block_1').children('.block-title').click(function () {
          $('#block-views-blog-block_1').find('.view-content').toggle();
          $(this).toggleClass("expanded");
        });
        $('#edit-field-blog-submit-dt-value-wrapper label:first-child').click(function () {
          $('#edit-field-blog-submit-dt-value-wrapper').children('.views-widget').toggle();
          $(this).toggleClass("expanded");
        });
        $('#edit-field-blog-category-value-wrapper label:first-child').click(function () {
          $('#edit-field-blog-category-value-wrapper').children('.views-widget').toggle();
          $(this).toggleClass("expanded");
        });
        $('#edit-field-blog-source-list-value-wrapper label:first-child').click(function () {
          $('#edit-field-blog-source-list-value-wrapper').children('.views-widget').toggle();
          $(this).toggleClass("expanded");
        });
        $('#edit-field-blog-submit-dt-value--2-wrapper label:first-child').click(function () {
          $('#edit-field-blog-submit-dt-value--2-wrapper').children('.views-widget').toggle();
          $(this).toggleClass("expanded");
        });
        $('#edit-field-blog-category-value--2-wrapper label:first-child').click(function () {
          $('#edit-field-blog-category-value--2-wrapper').children('.views-widget').toggle();
          $(this).toggleClass("expanded");
        });
      }

      $('.blog-item-container:empty').remove();
      if ($('#contentArea').children().hasClass('error')) {
        $('#edit-field-blog-submit-dt-value--2-wrapper').children('.views-widget').show();
        $('#edit-field-blog-submit-dt-value--2-wrapper label:first-child').addClass("expanded");
      }
    }

    */
    //End Blog specific script to be executed


    if ($("a[title='Business USA-Twitter']").length > 0) {
      $("a[title='Business USA-Twitter']").attr('target', '_blank');
    }
    if ($("a[title='Business USA-Linkedin']").length > 0) {
      $("a[title='Business USA-Linkedin']").attr('target', '_blank');
    }


    /*************************************************************************************************
     ***      **** This block of script is added by Mamatha Aerolla ***              **
     ***  The following script-tag holds 'view-more' 'view-less' functionality for        **
     ***  subcategory results in the Learn More Page of Export wizard              **
     *************************************************************************************************/


    if ($('body').hasClass('page-export')) {
      (function () {
        $(".export-information-section-links-mastercontainer ul.export-information-links").each(function () {

          if (jQuery(this).children("li").length > 7) {
            // show the first seven items
            jQuery(this).children("li").filter(':gt(6)').hide();
            // add view more link
            $(this).append('<li class="viewmore"><a href="javascript:void(0)"><span>+ View More ...</span></a></li>');
          }
        });
        $('.viewmore').click(function () {

          jQuery(this).siblings(':gt(6)').toggle();
          var span = jQuery(this).find('span').text();

          if (span == "+ View More ...") {
            $(this).find('span').text("- View Less");
          } else {
            $(this).find('span').text("+ View More ...");
          }
        });
      })();
    }

    /*************************************************************************************************
     ***      **** This block of script is specific to Micro-Sites pages              **
     *************************************************************************************************/


    if ($('body').hasClass('page-micro-site')) {
      //In the side bars - display only 3 results with 'view more' option
      $(".microsites-sidebarresults-results-container .view-content").each(function () {

        if (jQuery(this).find("div.views-row").length > 3) {
          // show the first three items
          jQuery(this).find("div.views-row").filter(':gt(2)').hide();
          // add view more link
          $(this).append('<a><div class="viewmore"><span>View All</span></div></a>');
        }
      });


      //side bars are collapsed by default
      $(".microsites-sidebarresults-results-container").hide();

      //side bars toggle functionality
      $('.microsite-sidebar-title-container').click(function () {
        $(this).siblings(".microsites-sidebarresults-results-container").toggle();
        $(this).toggleClass("expanded");
      });
    }

    /*************************************************************************************************
     ***      **** End of the block of script added for Export wizard by Mamatha Aerolla    **
     *************************************************************************************************/

    if ($('.wizard-goButton-inner').length > 0) {
      jQuery('.wizard-goButton-inner').bind('click', function () {
        var splashTextboxValue = jQuery('#txtgosearch').val();

        if (splashTextboxValue.length <= 0) {
          alert('Please enter your interests/needs in the text box and we would guide to a list of open opportunities that you may be eligible for');
          return null;
        } else {
          document.location.href = '/fboopen-widget/fboopen-search?input=' + splashTextboxValue + '&data_source=&naics=&parent_only=&p=';
        }
      });
      jQuery('#txtgosearch').bind('keypress', function (e) {
        if (e.keyCode == 13) {
          jQuery('.wizard-goButton-inner').click();
        }
      });
    }

    /* START: BusinessUSA SearchBox AutoComplete implementation */

    busaAutoCompleteTimerId = -1;
    busaAutoCompleteLastText = '';

    var autocompleteFunction = function () {
      var jqThis = jQuery('#sitewide-search-input');
      var givenSearchTerm = String(jqThis.val());

      //alert(givenSearchTerm);

      // Dont continue unless there is atleast 3 characters in the search-input
      if (givenSearchTerm.length < 1 || jQuery.trim(givenSearchTerm) == '') {
        consoleLog('Will not trigger triggerAutoCompleteForSearchBox() because there are less than 1 letter(s) in the search input.');
        return;
      }

      // Dont let this function trigger when the text in the inputbox dosnt change (i.e. when a user hits the shift key)
      if (busaAutoCompleteLastText == givenSearchTerm) {
        consoleLog('Will not trigger triggerAutoCompleteForSearchBox() because the search-input-text has not changed.');
        return;
      }
      busaAutoCompleteLastText = givenSearchTerm;

      // The browser may have its own native autocomplete iomplementation for form elemnts, disable this for the search-inputbox
      // jqThis.attr('autocomplete', 'off');

      // Dont trigger the AJAX session if the user him/herself did not change the search-input-textbox value...
      if (typeof ignoreSearchInputTextChange != 'undefined' && ignoreSearchInputTextChange == true) {
        consoleLog('Not going to trigger the AJAX session even though the search-input-textbox has changed, because it has changed due to the autocomplete-hover-event (ignoreSearchInputTextChange == true)');
        return;
      }

      // Hide the current AutoComplete box if one is currently being shown
      jQuery('.busa-autocomplete-responce-container').html('');
      jQuery('.busa-autocomplete-responce-container').hide();

      // We may be about to trigger an AJAX call for the AutoComplete result already, cancel this...
      if (busaAutoCompleteTimerId != -1) {
        clearTimeout(busaAutoCompleteTimerId);
        busaAutoCompleteTimerId = -1
      }

      // We shall trigger an AJAX call to /sys/autocomplete-ajax-handeler in 750ms
      busaAutoCompleteTimerId = setTimeout(function () {
        triggerAutoCompleteForSearchBox();
      }, 750);
    }

    // Event handeler for when a key is pressed when the search-inputbox has focus
    $("#sitewide-search-input").bind({
      keydown: autocompleteFunction,
      input: autocompleteFunction,
      propertychange: autocompleteFunction,
      keyup: autocompleteFunction
    })

    /* void triggerAutoCompleteForSearchBox()
     *
     * Calls "/sys/autocomplete-ajax-handeler?=~" and places returned HTML on the page appearing as
     * and auto complete box
     */
    function triggerAutoCompleteForSearchBox() {

      var jqSearchBox = jQuery('#sitewide-search-input');
      var searchInput = jqSearchBox.val();
      lastAutoCompleteSearchTerm = searchInput;

      if (typeof ignoreSearchInputTextChange != 'undefined' && ignoreSearchInputTextChange == true) {
        consoleLog('Not going to trigger the AJAX session even though the search-input-textbox has changed, because it has changed due to the autocomplete-hover-event (ignoreSearchInputTextChange == true)');
        return;
      }

      if (searchInput.length > 0) {

        // Add class that states an AutoComplete AJAX call is in progress
        //jQuery('#sitewide-search-input').attr('class', 'autocomplete-in-progress');

        /* Trigger call to /sys/autocomplete-ajax-handeler */
        consoleLog('Triggering BUSA-auto-complete AJAX session - triggerAutoCompleteForSearchBox()');
        jQuery.get('/sys/autocomplete-ajax-handeler?term=' + encodeURIComponent(searchInput), function (data) {

          /* At this point we have the responce. We expect there to be HTML code. This markup will  be placed directly under the search-inputbox */

          // Debug and verbosity
          busa_lastautocomplete_ajaxresponce = data;
          consoleLog('Obtained responce from /sys/autocomplete-ajax-handeler  The responce data can be seen in the global js var busa_lastautocomplete_ajaxresponce')
          //jQuery('#sitewide-search-input').attr('autocomplete', "on");
          // Remove class that states an AutoComplete AJAX call is in progress
          // jQuery('#sitewide-search-input').attr('class','');
          //jQuery('#sitewide-search-input').removeClass('autocomplete-in-progress');

          // Do not bother injecting anything or showing the autocomplete box if there is nothing returned by the AJAX responce


          if (String(data) == '') {
            return;

          }


          // We will inject the given HTML on the page into a div, which will be located directly under the search-inputbox, it will have the class "busa-autocomplete-responce-container"
          if (jQuery('.busa-autocomplete-responce-container').length == 0) {  // if there is no div with this class
            jQuery('.form-item-search-block-form').append('<div class="busa-autocomplete-responce-container"></div>'); // then create one
            consoleLog('div.busa-autocomplete-responce-container not found, creating (appending) one to .form-item-search-block-form')
          }

          // Inject the retrived HTML code from the ajax session
          jQuery('.busa-autocomplete-responce-container').html(data);
          jQuery('.busa-autocomplete-responce-container').show();
          jQuery('body').addClass('autocomplete-suggestions-isvisible');

          // Bind even handelers for changing the value in the search-input-box when the user hovers their mouse over an autocomplete item
          jQuery(document).bind('click', function () {
            jQuery('.busa-autocomplete-responce-container').html('');
            jQuery('.busa-autocomplete-responce-container').hide();
            jQuery('body').removeClass('autocomplete-suggestions-isvisible');
          });
          jQuery(".autocomplete-result").bind('mouseover', function (e) { /* Coder Bookmark: CB-0NAHBBX-BC */
            ignoreSearchInputTextChange = true;
            setTimeout(function () { /* Coder Bookmark: CB-9PVI116-BC */
              ignoreSearchInputTextChange = false;
            }, 1000);
            var text = jQuery(this).text();
            text = jQuery.trim(text);
            jQuery('#edit-search-block-form--2').val(text);
          });
          jQuery(".autocomplete-result").bind('mouseout', function () { /* Coder Bookmark: CB-WKJNU5H-BC */
            ignoreSearchInputTextChange = true;
            setTimeout(function () { /* Coder Bookmark: CB-9PVI116-BC */
              ignoreSearchInputTextChange = false;
            }, 1000);
            jQuery('#edit-search-block-form--2').val(lastAutoCompleteSearchTerm);
          });

        });
      }
    }

    /* END: BusinessUSA SearchBox AUtoComplete implementation */


    /* add class name 'ie8' to the html tag if browser is IE8 */
    if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
      var ieversion = new Number(RegExp.$1);

      if (ieversion == 8) {
        $('body').addClass('ie8');
      }
      if (ieversion == 9) {
        $('body').addClass('ie9');
      }
    }

    /* Get window width for responsive functions */
    windowWidth = $(window).width();

    //load DropKick plugin
    if ($('select').length > 0) {
      $('select').dropkick({
        mobile: true
      });
    }

    //load PrettyCheckable plugin
    if ($('input[type="checkbox"]').length > 0) {
      //$('input[type="checkbox"]').prettyCheckable();

      $('input[type="checkbox"]').each(function(){
        $(this).prettyCheckable();
      });

    }

    //load PrettyCheckable plugin for radio buttons inside wizards
    if ($('.slide .questions input[type="radio"]').length > 0) {
      $('.slide .questions input[type="radio"]').prettyCheckable();
    }

    //load PrettyCheckable plugin for radio buttons inside fboopen-widget/add_opportunity
    if ($('.page-fboopen-widget-add-opportunity .wrapper #contentArea .region-content input[type="radio"]').length > 0) {
      $('.page-fboopen-widget-add-opportunity .wrapper #contentArea .region-content input[type="radio"]').prettyCheckable();
    }

    // This horrible hack is necessary in order for the Pretty-checkable interface to update when other jQuery changes the value of a checkbox without triggering the click event
    if (typeof window.prettyFixingService == 'undefined') {
      window.prettyFixingService = setInterval(function () {
        jQuery('.prettycheckbox').each(function () { /* Coder Bookmark: CB-BSCLCF2-BC */
          var jqParent = jQuery(this);
          var jqInput = jqParent.find('input').first();
          var jqPrettyDisplay = jqParent.find('a').first();
          var checkboxIsChecked = jqInput.get(0).checked;
          var prettyIsChecked = jqPrettyDisplay.hasClass('checked');

          if (checkboxIsChecked != prettyIsChecked) {
            if (checkboxIsChecked) {
              jqPrettyDisplay.addClass('checked');
            } else {
              jqPrettyDisplay.removeClass('checked');
            }
          }
        });
      }, 250);
    }

    //help button dropdown
    if ($('#contentArea > .region, #titleWrapper').length > 0) {
      var helpTop = $('#contentArea > .region, #titleWrapper').offset().top;
      $('#helpButton').css('top', helpTop);
      $('#helpButton .helpToggle').click(function (e) {
        e.preventDefault();
        $('#helpButton ul').fadeToggle();
        $(this).toggleClass('active');
      });
    }

    $('#helpButton ul li a, #helpButton ul li').hoverIntent(
      function () {
        $(this).animate({
          backgroundColor: "#6885a2",
          color: "#fff"
        }, 200);
      }, function () {
        $(this).animate({
          backgroundColor: "#97e8c1",
          color: "#205589"
        }, 200);
      });

    /* equal heights cols*/
    function getMaxOfArray(numArray) {
      return Math.max.apply(null, numArray);
    }


    /******************************
     ***   DESKTOP RESOLUTION   ****
     ******************************/
    if (windowWidth > 1024 || $('body').hasClass('ie8')) {
      /* START - Exports Page */
      /*explore menu*/
      if ($('.export-menu').length > 0) {
        (function () {
          var menuTop = Math.floor($('.export-menu').offset().top) + 25;
          $('.export-menu').positionMe({
            parent: ".exporterdashboard-mastermastercontainer",
            margin: menuTop
          });
        })();
      }
      if ($('.export-portal-menu').length > 0) {
        (function () {
          var menuTop = Math.floor($('.export-portal-menu').offset().top);
          $('.export-portal-menu').positionMe({
            parent: ".exportdotgov-landingpage-maincontent",
            margin: menuTop
          });
        })();
      }
      /*End Explore Menu*/


      /* START - Swimlane Pages */

      if ($('.subnav-container').length > 0) {
        $('.subnav-container').positionMe('destroy');
        $('html, body').animate({ scrollTop: 0 }, 0);
        var menuTop = Math.floor($('.subnav-container').offset().top) + 1;
        $('.subnav-container').positionMe({
          parent: ".legacyswimlane-bodycontainer",
          margin: menuTop
        })
      }

      /* END - Swimlane Pages */


    }


    /******************************
     ***   TABLET RESOLUTION   ****
     *****************************/
    if (windowWidth >= 768 && windowWidth <= 1024 && !$('body').hasClass('ie8')) {

      /* help scroll switcher stuff */
      $(document).scroll(function () {
        $('.helpArea').css({display: $(this).scrollTop() > 1 ? "block" : "none"});
      });


      /* START - Request and Appointment Page */

      /* END - Request and Appointment Page */


      /* START - Exports Page */
      /*explore menu*/
      if ($('.export-menu').length > 0) {
        (function () {
          var menuTop = Math.floor($('.export-menu').offset().top) + 25;
          $('.export-menu').positionMe({
            parent: ".exporterdashboard-mastermastercontainer",
            margin: menuTop
          });
        })();
      }
      if ($('.export-portal-menu').length > 0) {
        (function () {
          var menuTop = Math.floor($('.export-portal-menu').offset().top);
          $('.export-portal-menu').positionMe({
            parent: ".exportdotgov-landingpage-maincontent",
            margin: menuTop
          });
        })();
      }
      /*End Explore Menu*/

      /*wizard section*/
      if ($('body').hasClass('page-contains-wizard')) {
        (function () {

        })();
      }
    }


    /******************************
     ***   MOBILE RESOLUTION   ****
     *****************************/
    if (windowWidth < 768) {

      /* create request an appointment link on front page */
      $('#block-views-wizard_list-block').after('<a href="/request-appointment-and-closest-resource-centers" class="mobileOnly locateResourceCenter" >Locate a Resource Center</a>')

    }


    /*****************************
     ***   MULTI RESOLUTION   ****
     ****************************/

    /*Google Translate Function*/
    var googleTranslate = $('#mainNavigation li.translate a');
    if (googleTranslate != 'undefined') {
      googleTranslate.attr('href', '#');
      googleTranslate.attr('style', 'cursor:pointer;')
      googleTranslate.attr('class', 'sitewide-googletranslate-ui-globe');
      var translate = $('<div />').attr('id', 'google_translate');
      if ($("div#google_translate").length == 0) {
        $('#mainNavigation li.translate .container').append(translate);
        $('#google_translate').empty();
        $('#google_translate').load('/sites/all/pages/google-translate.php');
      }
    }


    /*****************************
     ***   GovDelivery Advertise Functionality  ****
     ****************************/
    advertiseGovDeliveryOnWizardResults = function () {

      // If there is a cookie set to disable this popup, then bail
      if (readCookie('noGovDevPopUp') == 'NOPOPUP') {
        return;
      }
      
      // If this page is not rendered in the normal way through Drupal (i.e. this is a RepoPage that has a "solo" render mode), dont pop-up
      if ( jQuery('body').hasClass('font') === false && jQuery('body').hasClass('not-font') === false ) {
        return;
      }

      // At this point we know the user is on the "Results" slide, so...
      //consoleLog('User is on the "Results" slide, advertiseGovDeliveryOnWizardResults() will trigger funtionality in 30 seconds.');
      setTimeout(function () { // in 10 seconds...

        // Then show the popup
        jQuery.colorbox({html: '<form id="gov-delivery-colorbox" action="/subscribe"> \
                                            <div class="businessusalogo"></div> \
                                            <h2>Sign up to receive updates from BusinessUSA</h2> \
                                            <input id="email-subscribe-colorbox" class="email-subscribe" type="text" alt="Enter email address to receive Business USA updates." value="" name="subscribeEmail"></input> \
                                            <input class="form-text" id="submit-email" type="submit" value="Sign Up" alt="Click to submit your email address for Business USA updates signup." title="Click to submit your email address for Business USA updates signup."> \
                                            <div id="subscribe-no-thanks"><a href="#" onclick="jQuery(window).colorbox.close();">No Thanks</a></div> \
                                           </form>',
          onComplete: function () {
            jQuery('#email-subscribe-colorbox').focus();
          }
        });

        // The following line should be triggered in order to stop the popup from showing again to a user who USES THE POPUP and signs up
        createCookie('noGovDevPopUp', 'NOPOPUP', 30);

      }, 10000);
    }
    advertiseGovDeliveryOnWizardResults();


    /*****************************
     ***   MyUSA Implementation   ****
     ****************************/
    // If the user has a cookie stating he is logged in...
    if (readCookie('myusa_drupal_userid') != null && readCookie('myusa_drupal_userid') != "null") {

      // Change the "Login" link to a "Logout" link
      var loginLink = jQuery('#block-system-main-menu .menu a:contains("Login")');
      loginLink.text('Logout');
      loginLink.attr('note', 'This link and its href has been altered from global.js,Coder Bookmark: CB-DCIA69A-BC');
      loginLink.attr('href', 'javascript: if ( confirm(\'Are you sure you want to logout?\') ) { createCookie(\'myusa_drupal_userid\', \'null\', 30); jQuery(\'body\').append(\'<iframe id="myusasignout" onload="window.document.location.reload();" src="http://staging.my.usa.gov/sign_out"></iframe>\'); } void(0);');
      loginLink.attr('title', '');

      // If this is the Front Page...
      if (jQuery('body').hasClass('front')) {

        consoleLog('This is the FrontPage, and the user has a myusa_drupal_userid cookie, loading the MyUSA front-apge through Ajax ');

        // Show spinner/loading message
        jQuery('.region-frontcontent').html('');
        jQuery('.myusa-loading-msg').show();

        // Load the MyUSA Front Page through AJAX from /usr-dashboard/frontpage
        var myusa_drupal_userid = readCookie('myusa_drupal_userid');
        var htmlSrcURL = '/usr-dashboard/frontpage?render_mode=solo&uid=' + myusa_drupal_userid;
        consoleLog('Loading MyUSA FrontPage HTML-source from ' + htmlSrcURL);
        jQuery.get(htmlSrcURL, function (injectHTML) {

          // Hide spinner
          jQuery('.region-frontcontent').hide();
          jQuery('.myusa-loading-msg').hide();

          // Inject HTML and dependencies
          jQuery('.region-frontcontent').html(injectHTML);
          jQuery.getScript('/repo_pages_module/js-css-read?path=sites/all/pages/usr-dashboard/frontpage/.page.js');

          // Present the content to the user
          jQuery('.region-frontcontent').fadeIn();
        });
      }
    }
    
    
    
    
      jQuery('input.auto-fill-zip-code').bind('change', function ()
      {
          var jqThis = jQuery(this);
            if ( jqThis.val() == '' )
                {
                   jqThis.val('20006');
                    jqThis.attr('placeholder', '20006');
                }


      });

    /*****************************
     ***   508 Compliance     ****
     ****************************/
    setTimeout(function() {
        // Set a timeout to allow users to manually turn off stylesheets on their
        // devices.
        if (typeof document.styleSheets == 'undefined' || $('html').css('font-size') != '10px') {
            // Stylesheets are forced not to load.

            var path = window.location.pathname;
            // Homepage.
            if (path == '/') {
                var featuredImg = $('.view-front-page-news-views .list-group a');
                if (featuredImg.length > 0) {
                    featuredImg.each(function() {
                        var itemLink = $(this).attr('href');
                        $(this).children('img').remove();
                        $(this).text(itemLink);

                        var item = $(this).parents('.panel.panel-carousel.slide').children('.panel-heading');
                        if (item.children().text() == 'Featured Item') {
                          item.children().remove();
                          item.append('<a href="' + itemLink + '"><h3 class="panel-title">Featured Item</h3></a>');
                        }
                    });

                    $('.bx-viewport .slider .panel.bx-clone').remove();

                    // Remove unnecessary map elements.
                    $('#map-canvas').remove();

                    // Remove the embeded view slideshow and fall back to the hidden static link list display.
                    $('#frontpage-successstories').remove();
                }
            }
        }
    }, 10000);

    createFlyoutNotification
    
  });

  // Hide the notification when the close button is clicked.
  $(document).on('click', '.recommended-flyout-close', function(e) {
    $('.recommended-flyout-wrap').parent().children(":visible").hide("slide", { direction: "right" }, 1000);
  });

  // Fixes the scroll navbar search box to redirect the user to the search page.
  $(document).on('click', '#scrollNavigation .input-group .input-group-btn button', function() {
    var searchItem = $('#scrollNavigation .input-group input').val();
    window.location.replace('/search/site/' + searchItem);
  });

})(jQuery);
