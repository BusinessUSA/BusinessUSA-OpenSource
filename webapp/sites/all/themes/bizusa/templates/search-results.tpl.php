<?php 
    // The $search_term variable will be used later in this template
    $url_path = explode('/', $_GET['q']);
    $search_term = $url_path[2];
?>

    <?php if ( !empty($variables['response']->response->numFound) ): ?>
        <script>
            /* The following JavaScript is added from search-results.overridable.tpl.php */
            // Add the search result count as a class onto the body
            jQuery(document).ready( function () {
                jQuery("body").addClass("search-result-count-<?php print $variables['response']->response->numFound; ?>");
                <?php if ( intval($variables['response']->response->numFound) > 0 ): ?>
                    jQuery("body").addClass("more-than-0-search-results");
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

<?php if ( stripos($search_term, 'grant') !== false ) { ?>
    <!-- Search Results Teaser section (for when the user searches for "grants") - the following div is stored in search-results.overridable.tpl.php
    The following div is display:none by default, and will be shown by jQuery when necessary, search for 2854D55 for this jQuery -->
    <div class="grants-search-messages-container" style="display: none;">
        <div class="constant-search-messages-closeicon-container">
                    <input type="button" class="constant-search-messages-closeicon" value="Close this help message"
                    onclick="setGrantsHelpMessageVisibilityFlag(false); jQuery('.grants-search-messages-container').fadeOut();" />
        </div>
        <div class="search-results-welcome-top-message"><p>BusinessUSA does NOT provide grants for starting and expanding a business.</p>
                <p>Government grants are funded by your tax dollars and, therefore, require very stringent compliance and reporting measures to ensure the money is well spent.
                As you can imagine, grants are not given away indiscriminately. Click <a href="#" onclick="javascript: window.open('http://business.usa.gov/article/facts-about-government-grants-0');">here</a> to read more.</p>
        </div>
    </div>
<?php } ?>
    
<!-- Search Results Teaser section (for when the user searches for "permit me") - the following div is stored in search-results.overridable.tpl.php-->
<?php if ( stripos($search_term, 'permit me') !== false || stripos($search_term, 'permitme') !== false ) { ?>
    <div class="permit-search-messages-container" style="display: none;">
        <div class="constant-search-messages-closeicon-container">
                <input type="button" class="constant-search-messages-closeicon" value="Close this help message"
                onclick="setPermitHelpMessageVisibilityFlag(false); jQuery('.permit-search-messages-container').fadeOut();" />
        </div>
        <div class="search-results-welcome-top-message">
            <p>
                "Permit Me" was a legacy tool of www.business.gov, but currently this functionality is included in the
                <a href="#" onclick="javascript: window.open('/start-a-business');">"Starting a Business"</a> wizard.
            </p>
        </div>
    </div>
<?php } ?>

<!-- Search Results Teaser section (for when the user searches for "department of defense") - the following div is stored in search-results.overridable.tpl.php-->
<?php
    $searchTeasers = array();
    
    if ( strpos(strtolower($search_term), 'department') !== false && strpos(strtolower($search_term), 'defense') !== false ) {
        $searchTeasers[] = '
            <a href="/search/site/defense%20contracting">
                Do you want to see resources related to defense contracting?
            </a>
        ';
        $searchTeasers[] = '
            <a href="/search/site/department%20of%20defense?f[0]=bundle%3A%28services%20OR%20program%20OR%20tools%20OR%20article%29">
                Do you want to look at resources offered by the department of defense?
            </a>
        ';
    }
?>
<div class="searchresults-teasers-container searchresults-teasercount-<?php print count($searchTeasers); ?>" rendersource="search-results-teasers.tpl.php">
    <?php foreach ( $searchTeasers as $searchTeaser ) { ?>
        <div class="searchresults-teaser-container">
            <?php print $searchTeaser; ?>
        </div>
    <?php } ?>
</div>

<!-- Top wizards section invoked from search-results.tpl.php /* Coder Bookmark: CB-Q0DG1V3-BC */ -->
    <?php print theme('search_results_related_wizards', array('search' => $search_term)); ?>
<!-- End Coder Bookmark: CB-Q0DG1V3-BC -->

<div class="search-export-dialogue" style="display: none;" >

    <!-- <div class="vendorLightBox-logo" >
        <div class="businessusalogo"></div>

    </div> -->
    <div class="vendorLightBox">
        <h2 style="margin-top:0px;">Download Search Results</h2>
        <p id="cbox-results">Total of <?php echo number_format($variables['response']->response->numFound); ?> records available for download.</p>

        <form method="post" action="/download_search_xls2" id="search-export-form" onsubmit="return validateExportForm();">
            <h3>Page Selection</h3>
            
                    <?php if($variables['response']->response->numFound > 10) { ?>
                    <input type="radio" name="dc" value="<?php echo 'current_' . $_REQUEST['page']; ?>" checked="true" />&nbsp;Current Page
                        <?php
                            if($variables['response']->response->numFound <= 100 && $variables['response']->response->numFound > 10){
                                print '<input type="radio" name="dc" value="'. $variables['response']->response->numFound .'"/>&nbsp;All Pages';
                            }
                        ?>
                    <?php }else if($variables['response']->response->numFound <= 10){
                                print '<input checked="true" type="radio" name="dc" value="'. $variables['response']->response->numFound .'"/>&nbsp;All Search Results';
                        } ?>

                        <br>

                <?php if($variables['response']->response->numFound > 10){?>
                    
                             <input type="radio" name="dc" value="range"/>
                                &nbsp;Pages from&nbsp;
                             <input type="text" name="rangefrom" size="5" onfocus="selectRange();"/>
                                &nbsp;to&nbsp;
                             <input type="text" name="rangeto" size="5" onfocus="selectRange();"/>
                             <br clear="all">
                             <span id="export-message"><small>(Max allowed pages: 10)</small></span>
                         
                <?php } ?>


            
            <div id="error"><label id="errorMsg"></label></div>
            <textarea type="hidden" id="no_of_pages"  class="hidden never-show-ever"><?php echo ceil(intval($variables['response']->response->numFound)/10); ?></textarea>
            <textarea type="hidden" class="hidden never-show-ever params_serialized" name="params_serialized" id="params_serialized"><?php echo serialize( $variables['query']->getSolrParams() ); ?></textarea>
            <div class="clearfix">
              <input type="submit" class="export-to-excel btn btn-primary btn-small" value="Download"
                   DevNote="this link targets a Basic Page stored in the Drupal database" />&nbsp;&nbsp;
              <input type="button" class="btn btn-primary btn-small" id="export_dialogue_close" value="Close" onclick="close_export_dialogue();"/>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript" src="<?php print '/sites/all/themes/bizusa/scripts/pages/search_results.js'; ?>"></script>

<script>
    jQuery(document).ready( function () {
        jQuery('.export-to-excel-button').bind('click', function () {
            jQuery.colorbox({
                html: jQuery('.search-export-dialogue').html(),
                onComplete: function () {
                    jQuery('#colorbox input[name=dc]:visible').eq(0).focus();
                }
            });
        });
    });
    function selectRange(){
        jQuery('input:radio[value=range]').attr('checked', 'checked');
    }
    function close_export_dialogue(){
        jQuery('#colorbox #cboxClose').click();
    }
    function isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
    function validateExportForm() {
        jQuery('#colorbox #error').hide();
        var checked = jQuery('#colorbox input[name=dc]:checked').val();
        var rangeFrom = jQuery('#colorbox input[name=rangefrom]').val();
        var rangeTo = jQuery('#colorbox input[name=rangeto]').val();
        var totalPages = jQuery('#colorbox #no_of_pages').val();

        if(checked == 'range'){
           if(rangeFrom == '' || rangeTo == ''){
               jQuery('#colorbox #error').show();
               jQuery('#colorbox #errorMsg').html('Please enter a range.');
               return false;
           }else if(rangeFrom <= 0 || rangeTo <= 0 || !isNumeric(rangeFrom) || !isNumeric(rangeTo)){
               jQuery('#colorbox #error').show();
               jQuery('#colorbox #errorMsg').html('Please Enter a valid range.');
               return false;
           }else if( (rangeFrom.toString().indexOf('.') != -1) || (rangeTo.toString().indexOf('.') != -1)){
               jQuery('#colorbox #error').show();
               jQuery('#colorbox #errorMsg').html("Page number can not be float.");
               return false;
           }else if(parseInt(rangeFrom) > parseInt(rangeTo)){
              jQuery('#colorbox #error').show();
              jQuery('#colorbox #errorMsg').html("'From' page number should be less than the 'To' page number.");
              return false;
           }else if(parseInt(rangeFrom) > parseInt(totalPages)){
               jQuery('#colorbox #error').show();
               jQuery('#colorbox #errorMsg').html("'From' page number should not be greater than the total number of pages.");
               return false;
           }else if((parseInt(rangeTo)-parseInt(rangeFrom)) + 1 > 10){
               jQuery('#colorbox #error').show();
               jQuery('#colorbox #errorMsg').html('You can only download 10 pages at a time.');
               return false;
           }else{
               return true;
           }
        }
    }
    
</script>
<?php
    $url_parts = explode('/', $_GET['q']);
	$size = sizeof($url_parts);
	if(isset($_REQUEST['mode']) && $size > 2){ 
	   print "<div><h3><i>You searched for: " . $url_parts[$size-1] . "</i></h3></div>";
	}
?>

<!-- jQuery Select Menu override for Search select -->
<link rel="Stylesheet" href="/sites/all/modules/contrib/jquery_update/replace/ui/themes/base/jquery-ui.css" type="text/css" />
<?php drupal_add_js("sites/all/modules/contrib/jquery_update/replace/ui/ui/minified/jquery-ui.min.js", 'file'); ?>

<div class="clearfix"></div>

<?php if ($search_results): ?>

  <!-- Search results begin here (search-results.overridable.tpl.php) -->
  <span class="search-results <?php print $module; ?>-results">
    <?php print $search_results; ?>
  </span>
  
  <?php print $pager; ?>
<?php else : ?>
  <h2><?php print t('Your search yielded no results');?></h2>
  <?php print search_help('search#noresults', drupal_help_arg()); ?>
<?php endif; ?>

<!-- If the user as searched for something 3 times within a 45 min time-span, ask them if they need help -->
<!-- The following script tag is stored in search-results.overridable.tpl.php --> 
<div class="constant-search-messages-mastercontainer" style="display: none;">
    <!-- The following div is stored in search-results.overridable.tpl.php and injected here from a script within the same file --> 
    <div class="constant-search-messages-container">
        <!-- The following HTML is stored in search-results.overridable.tpl.php and injected here from a script within the same file --> 
        <div class="constant-search-messages-closeicon-container">
            <input type="button" class="constant-search-messages-closeicon" value="Close this help message" onclick="setFrequentSearchHelpMessageVisibilityFlag(false); jQuery('.constant-search-messages-container').fadeOut();" />
        </div>
        <div class="constantsearch-message constantsearch-message-1">
            Do you need help? Please call 1-800-FED-INFO (1-800-333-4636) or <a href="xhttp://help.business.usa.gov/ics/support/ticketnewwizard.asp?style=classic&deptID=30030&">Ask a Question</a>
        </div>
    </div>
</div>

<!-- Show a message to the user asking if they need help when a given user uses the search engine more than X times in a Y-min timespan -->
<!-- The following script tag is stored in search-results.overridable.tpl.php -->
<script>
    jQuery(document).ready( function () {
        
        // If the user has been to the search results page X times in the past Y minutes, then set a cook that says a "frequent-searching"-help-message should appear for the next 10 minutes.
        var page = getQueryParameter('page');
        var auto = getQueryParameter('auto');
        var facet = getQueryParameter('f[0]');
        if((page == "null" && facet == "null") || auto == 1){
            if ( userHasBeenSearchingFrequently() == true ) {
                setFrequentSearchHelpMessageVisibilityFlag(true);
            }

            if ( getFrequentSearchHelpMessageVisibilityFlag() == true ) {
                // Show message to user asking if they need help
                var messageHTML = String( jQuery('.constant-search-messages-mastercontainer').html() );
                jQuery(messageHTML).insertAfter('#breadcrumb') // Show this message directly after the breadcrumb
            }

        }
        
        // Determin weather or not the div.grants-search-messages-container should be shown. This div is not guaranteed to exist in the DOM (this is dependant on the user=search-input)  
        jQuery('.grants-search-messages-container').hide();
        if ( getGrantsHelpMessageVisibilityFlag() == true ) { // Coders Bookmark: 2854D55
            jQuery('.grants-search-messages-container').fadeIn();
        }

         // Determinee weather or not the div.permit-search-messages-container should be shown. This div is not guaranteed to exist in the DOM (this is dependant on the user=search-input)
        jQuery('.permit-search-messages-container').hide();
        if ( getPermitHelpMessageVisibilityFlag() == true ) { // Coders Bookmark: 2854D55
            jQuery('.permit-search-messages-container').fadeIn();
        }
        
    });


    /** void setGrantsHelpMessageVisibilityFlag(bool)
      *
      * Used to manipulate the output of the getFrequentSearchHelpMessageVisibilityFlag() function.
      * If the given argument is TRUE, then the "Grants"-help-message is allowed to be shown.
      * If the given argument is FALSE, then the "frequent-searching"-help-message will be terminated for the next 10 minutes.
      * This client-side JavaScript function depends on browser-cookies in order to function correctly.
      */
    function setGrantsHelpMessageVisibilityFlag(doShowMessage) {
        if ( doShowMessage == false ) {
            createCookie('killGrantsHelpMsg', getCurrentUnixEpochTime(), 3);
        } else {
            createCookie('killGrantsHelpMsg', 0, 3);
        }
    }

    /** bool getFrequentSearchHelpMessageVisibilityFlag()
     *
     * Returns TRUE if setFrequentSearchHelpMessageVisibilityFlag(FALSE) was NOT within the last 10 minutes.
     * This client-side JavaScript function depends on browser-cookies in order to function correctly.
     */
    function getGrantsHelpMessageVisibilityFlag() {

       var killGrantsHelpMsg = parseInt( readCookie('killGrantsHelpMsg') );
       var secondsIn10Minutes = 600;

       if ( isNaN(killGrantsHelpMsg) || killGrantsHelpMsg === 0 || (getCurrentUnixEpochTime() - killGrantsHelpMsg) > secondsIn10Minutes) {
           return true;
       } else {
           return false;
       }
    }

    /** void setPermitHelpMessageVisibilityFlag(bool)
         *
         * Used to manipulate the output of the getFrequentSearchHelpMessageVisibilityFlag() function.
         * If the given argument is TRUE, then the "Grants"-help-message is allowed to be shown.
         * If the given argument is FALSE, then the "frequent-searching"-help-message will be terminated for the next 10 minutes.
         * This client-side JavaScript function depends on browser-cookies in order to function correctly.
         */
       function setPermitHelpMessageVisibilityFlag(doShowMessage) {
           if ( doShowMessage == false ) {
               createCookie('killPermitHelpMsg', getCurrentUnixEpochTime(), 3);
           } else {
               createCookie('killPermitHelpMsg', 0, 3);
           }
       }

       /** bool getPermitHelpMessageVisibilityFlag()
        *
        * Returns TRUE if setFrequentSearchHelpMessageVisibilityFlag(FALSE) was NOT within the last 10 minutes.
        * This client-side JavaScript function depends on browser-cookies in order to function correctly.
        */
       function getPermitHelpMessageVisibilityFlag() {

          var killPermitHelpMsg = parseInt( readCookie('killPermitHelpMsg') );
          var secondsIn10Minutes = 600;

          if ( isNaN(killPermitHelpMsg) || killPermitHelpMsg === 0 || (getCurrentUnixEpochTime() - killPermitHelpMsg) > secondsIn10Minutes) {
              return true;
          } else {
              return false;
          }
       }
    /** void setFrequentSearchHelpMessageVisibilityFlag(bool)
      *
      * If the given argument is TRUE, then sets the "frequent-searching"-help-message to be shown for the next 10 minuts.
      * If the given argument is FALSE, then the "frequent-searching"-help-message will be terminated, and the lastSearches cookie will be reset.
      * This client-side JavaScript function depends on browser-cookies in order to function correctly.
      */
    function setFrequentSearchHelpMessageVisibilityFlag(doShowMessage) {
        if ( doShowMessage == true ) {
            createCookie('displayFreqSearchHelpMsg', getCurrentUnixEpochTime(), 3);
        } else {
            createCookie('displayFreqSearchHelpMsg', 0, 3);
            lastSearches = ['', '', ''];
            createCookie('lastSearches', JSON.stringify(lastSearches), 3);
        }
    }
    
    /** bool getFrequentSearchHelpMessageVisibilityFlag()
      *
      * Returns TRUE if setFrequentSearchHelpMessageVisibilityFlag(true) was called less than 10 minutes ago.
      * This client-side JavaScript function depends on browser-cookies in order to function correctly.
      */
    function getFrequentSearchHelpMessageVisibilityFlag(doShowMessage) {
    
        var lastHelpMsgSet = parseInt( readCookie('displayFreqSearchHelpMsg') );
        var secondsIn10Minutes = 600;
        
        if ( getCurrentUnixEpochTime() - lastHelpMsgSet < secondsIn10Minutes ) {
            return true;
        } else {
            return false;
        }
    }
    
    /** bool userHasBeenSearchingFrequently()
      *
      * Returns TRUE when the user has been to a search results page 3 or more times within the past 15 minutes.
      * This client-side JavaScript function depends on browser-cookies in order to function correctly.
      */
    function userHasBeenSearchingFrequently() {
    
        // Load or initalize the lastSearches variable - which shall be an array of unix-epoc times there user has been to a search results page. This array shall max a max of 3 elements.
        var lastSearches = readCookie('lastSearches');
        if ( lastSearches == null ) {
            lastSearches = ['', '', ''];
        } else {
            lastSearches = JSON.parse(lastSearches);
        }
        
        // Determin the current unix-epoc time
        var unixTimeNow = getCurrentUnixEpochTime(); // getCurrentUnixEpochTime() is defined in global.js
        
        // Note that the user has just searched for something in the lastSearches variable, only keep 3 elements in this array by removing the last element when adding a new one in.
        lastSearches.unshift(unixTimeNow);
        lastSearches.pop();

        // If the past 3 searches were all within the past 15 minutes...
        var allSearchesInPast15Mins = true;
        var secondsIn15Mins = 900;
        for ( var x = 0 ; x < lastSearches.length ; x++ ) {
            if ( lastSearches[x] < unixTimeNow - secondsIn15Mins ) {
                allSearchesInPast15Mins = false;
            }
        }
        
        // Save the lastSearches variable [changes (if any)] into cookies
        createCookie('lastSearches', JSON.stringify(lastSearches), 3);
        
        return allSearchesInPast15Mins;
    }

    //This function will parse http query string and return value of requested parameter.
    function getQueryParameter ( parameterName ) {
      var queryString = window.top.location.search.substring(1);
      var parameterName = parameterName + "=";
      if ( queryString.length > 0 ) {
        begin = queryString.indexOf ( parameterName );
        if ( begin != -1 ) {
          begin += parameterName.length;
          end = queryString.indexOf ( "&" , begin );
            if ( end == -1 ) {
            end = queryString.length
          }
          return queryString.substring ( begin, end );
        }
      }
      return "null";
    }
</script>
