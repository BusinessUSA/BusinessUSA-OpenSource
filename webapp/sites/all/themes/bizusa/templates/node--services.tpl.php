<style>
  body.page-node .region-content {
      padding: 0%;
  }
  body > .wrapper .region.region-content {
    background-color: #fff;
  }
</style>

<?php
    // Include the ReCaptcha library
    require_once('recaptchalib.php');
    
    // Obtain the Node-ID for the node being views for this page
    $thisNode = menu_get_object(); // This function loads the node that this landing page is showing\
    $landingPageNodeId = $thisNode->nid;

    // Lookup "related" content to this node - This functionality is handled in SimilarProgramsAndServices.php
    $relatedProgsServs = similar_content_by_tracking(array('services', 'program'), $landingPageNodeId, 5);
    $relatedEvents = similar_content_by_tracking('event', $landingPageNodeId, 5);
    
    // Lookup "related" content to this node (based on a Solr search) - This functionality is handled in SimilarProgramsAndServices.php
    $relatedProgsServs = array_merge($relatedProgsServs, similar_content_by_solr(array('services', 'program'), $landingPageNodeId, 5));
    $relatedEvents = array_merge($relatedEvents, similar_content_by_solr(array('event'), $landingPageNodeId, 5));
    
    /* Assuming that looking up related nodes based on the SimilarProgramsAndServices.php and Solr returned little or 
    no infomation, assume the latest 5 nodes created */
    $relatedProgsServs = array_merge(
        $relatedProgsServs, 
        node_load_multiple( 
            db_query("
                SELECT nid, nid 
                FROM node
                WHERE ( type = 'program' OR type = 'services' )  AND status = 1 
                ORDER BY created DESC LIMIT 5 
            ")->fetchAllKeyed(0)
        )
    );
    $relatedProgsServs = array_merge(
        $relatedProgsServs, 
        node_load_multiple( 
            db_query("
                SELECT nid, nid 
                FROM node
                WHERE type = 'event' AND status = 1 
                ORDER BY created DESC LIMIT 5 
            ")->fetchAllKeyed(0)
        )
    );
    
    // Decide weather we shall use single, or double coulmn layout
    $dblCoulmnLayout = false;
    if ( !empty($node->field_services_public_poc_email['und'][0]['value']) || !empty($node->field_services_public_poc_phone['und'][0]['value']) ) {
        $dblCoulmnLayout = true;
    }
    
?>

<!-- The following markup is generated from <?php print __FILE__; ?> /* Coder Bookmark: CB-QH09PNX-BC */ -->
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>" rendersource="<?php print basename(__FILE__); ?>">

    <!-- The following script tag is just some dev-notes. REMOVE THIS SCRIPT-TAG AFTER SEPTEMBER 2014 -->
    <script>
        /* REMOVE THIS SCRIPT-TAG AFTER SEPTEMBER 2014  */
        jQuery(document).ready( function () {
            if ( jQuery('#block-boxes-customer_recommended_data').length > 0 || jQuery('#block-boxes-customer_rec_data_services').length > 0 ) {
                alert('You should not have the "Similar Programs" block loaded through Boxes anymore. Please either remove them from as mentioned in the manual configs for the September 2014 release (for ticket BUSUSA-3686), \n\nOR...\n\n...you can just import the QA database into your local again.');
                window.open('http://wiki.reisys.com:8080/display/USASv2/Business.USA+Deployment+2014-09-24');
            }
        });
    </script>

    <div class="row no-margin node-landing-toparea">
    
        <div class="<?php if ( $dblCoulmnLayout ){ print 'col-sm-6';}else{ print 'col-sm-12';} ?>"> 
            <div class="detail-descr">
                <?php print check_markup($node->field_services_detail_desc['und'][0]['value'], $node->field_services_detail_desc['und'][0]['format']); ?>
            </div>
            
            <!-- LEARN MORE (LINK) -->
            <?php if ( !empty($node->field_services_ext_url['und'][0]['url']) ): ?>
                <div class="node-fieldlabelvalue field_program_ext_url">
                    <span class="fieldlabel">Learn More: </span>
                    <span class="fieldvalue">
                        <a href="<?php print $node->field_services_ext_url['und'][0]['url']; ?>">
                            <?php print $node->field_services_ext_url['und'][0]['url']; ?>
                        </a>
                    </span>
                </div>
            <?php endif; ?>
            
            <div class="node-fieldlabelvalue tags_column">
                <span class="fieldlabel">Tags: </span>
                <span class="fieldvalue">
                    <?php
                        $tagsToShow = array();
                        foreach ( $node->field_tagged_terms['und'] as $termInfo ) {
                            $tid = $termInfo['tid'];
                            $term = taxonomy_term_load( $tid );
                            $tagsToShow[] =
                                '<a class="tag-search-link" href="/search/site/%2A?f[0]=bundle:program&f[1]=im_field_tagged_terms:' . $tid . '">'
                                . $term->name
                                . '</a>';
                        }
                        if ( count($tagsToShow) > 0 ) {
                            print implode(', ', $tagsToShow);
                        } else {
                            print '<i class="notags">No tags associated at this time</i>'; 
                        }
                    ?>
                </span>
            </div>
            <a class="showtagui" href="javascript: jQuery('#addtags').hide().fadeIn(); void(0);">
                Click here to suggest additional keyword tags
            </a>

            <div id="addtags" style="display: none;">
                <form action="javascript: jQuery.post('/sys/suggest-tag-receiver', jQuery('#tagsform').serialize(), function(data) { jQuery.colorbox( {'html': data} ); }); void(0);" id='tagsform'>

                    <input type="hidden" name="nid" value="<?php print $landingPageNodeId; ?>" />
                    <div class="tag-labelinput">
                        <label for="tags">
                            Enter your suggested tags here, separated by commas
                        </label>
                        <input class="form-control" placeholder="Technology, Transfer, Federal" name="tags" id="tags" type="text" />
                    </div>
                    <br>
                    <!-- ReCaptcha - Coder Bookmark: CB-QA5IM6X-BC in <?php print basename(__FILE__); ?> -->
                    <script type="text/javascript" src="http://api.recaptcha.net/challenge?k=6LeYk_ASAAAAABG1F5Zo3TWq277NYXpjpvm2ai4q"></script>
                    <noscript>
                        <iframe src="http://api.recaptcha.net/noscript?k=6LeYk_ASAAAAABG1F5Zo3TWq277NYXpjpvm2ai4q" height="300" width="500" frameborder="0"></iframe><br/>
                        <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                        <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
                    </noscript>

                    <input type="submit" value="Submit" class="btn btn-brand tags-submit" />
                    <input type="button" value="Cancel" onclick="javascript: jQuery('#addtags').slideUp(); void(0);" class="btn btn-primary tags-cancel"/>

                </form>
            </div>
            
        </div>
        
        <?php if ( $dblCoulmnLayout ): ?>
            <div class="<?php if ( $dblCoulmnLayout ) print 'col-sm-6'; ?>" >
                
                <!-- CONTACT INFO -->
                <div class="contactinfo-container">
                    <h2>Contact</h2>
                    <?php if ( !empty($node->field_services_public_poc_name['und'][0]['value']) ): ?>
                        <!-- CONTACT ORGANIZATION-->
                        <div class="contactinfo-organization">
                            <?php 
                                $fieldInfo = field_info_field('field_services_office_in_org');
                                $keyToLabelMappings = $fieldInfo['settings']['allowed_values'];
                                $key = $node->field_services_office_in_org['und'][0]['value'];
                                print $keyToLabelMappings[$key];
                            ?>
                        </div>
                    <?php endif; ?>
                    <ul class="contactinfo-list">
                    
                        <?php if ( !empty($node->field_services_public_poc_name['und'][0]['value']) ): ?>
                            <li class="contactinfo-item">
                                <!-- CONTACT NAME-->
                                <span class="contactinfo-fieldvalue contactinfo-contactname">
                                    Contact Name:
                                </span>
                                <span class="contactinfo-fieldvalue">
                                    <?php print $node->field_services_public_poc_name['und'][0]['value']; ?>
                                </span>
                            </li>
                        <?php endif; ?>
                        
                        <?php if ( !empty($node->field_services_public_poc_email['und'][0]['value']) ): ?>
                            <li class="contactinfo-item">
                                <!-- CONTACT EMAIL-->
                                <span class="contactinfo-fieldvalue contactinfo-contactemail">
                                    Contact E-Mail:
                                </span>
                                <span class="contactinfo-fieldvalue">
                                    <a href="mailto: <?php print $node->field_services_public_poc_email['und'][0]['value']; ?>">
                                        <?php print $node->field_services_public_poc_email['und'][0]['value']; ?>
                                    </a>
                                </span>
                            </li>
                        <?php endif; ?>
                        
                        <?php if ( !empty($node->field_services_public_poc_phone['und'][0]['value']) ): ?>
                            <li class="contactinfo-item">
                                <!-- CONTACT PHONE NUMBER-->
                                <span class="contactinfo-fieldvalue contactinfo-phonenumber">
                                    Phone Number:
                                </span>
                                <span class="contactinfo-fieldvalue">
                                    <a href="tel: <?php print $node->field_services_public_poc_phone['und'][0]['value']; ?>">
                                        <?php print $node->field_services_public_poc_phone['und'][0]['value']; ?>
                                    </a>
                                </span>
                            </li>
                        <?php endif; ?>
                        
                    </ul>
                </div>
            
            </div>
        <?php endif; ?>
        
    </div>
    

    <div class="row no-margin">

        <div class="col-sm-6">    
            <!-- The following markup is generated from <?php print __FILE__; ?> -->
            <div class="panel panel-default add-bg-light-gray has-results add-large-padding" rendersource="<?php print basename(__FILE__); ?>">
                <div class="panel-heading"><h3 class="panel-title">Related Content</h3></div>
                <!-- List group -->
                <div class="panel-body">
                    <?php  if ( count($relatedProgsServs) === 0 ): ?>
                        <div class="panel-result">No matched results found.</div>
                    <?php else: ?>
                        <?php foreach ( array_slice($relatedProgsServs, 0, 5) as $relatedNode ): ?>
                            <div class="panel-result">
                                <a href="/<?php print drupal_get_path_alias('node/' . $relatedNode->nid); ?>">
                                    <?php print $relatedNode->title; ?>
                                </a>
                                <div class="description">
                                    <?php
                                        if ( $relatedNode->type === 'program' ) {
                                            print truncate_utf8(strip_tags($relatedNode->field_program_detail_desc['und'][0]['value']), 150, true, true);
                                        } else {
                                            print truncate_utf8(strip_tags($relatedNode->field_services_detail_desc['und'][0]['value']), 150, true, true);
                                        }
                                    ?>
                               </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        
        <div class="col-sm-6"> 
            <!-- The following markup is generated from <?php print __FILE__; ?> -->
            <div class="related-events panel panel-default add-bg-light-gray has-results add-large-padding" rendersource="<?php print basename(__FILE__); ?>">
                <div class="panel-heading"><h3 class="panel-title">Related Events</h3></div>
                <div class="panel-body">
                    <?php  if ( count($relatedEvents) === 0 ): ?>
                        <div class="panel-result">No matched results found.</div>
                    <?php else: ?>
                        <?php foreach ( array_slice($relatedEvents, 0, 5) as $relatedNode ): ?>
                            <div class="panel-result">
                                <a href="<?php print drupal_get_path_alias('node/' . $relatedNode->nid); ?>">
                                    <?php print $relatedNode->title; ?>
                                </a>
                                <div class="description">
                                    <?php  print truncate_utf8($relatedNode->field_event_detail_desc['und'][0]['value'], 250, true, true); ?>
                                </div>
                                <div class="dates">
                                    <?php 
                                        $dt = new DateTime($relatedNode->field_event_date['und'][0]['value'] . ' UTC');
                                        print $dt->format('M jS g:ia');
                                    ?>
                                </div>
                                <div class="location">
                                    <?php 
                                        $address = '';
                                        if ( !empty($relatedNode->field_event_state['und'][0]['value']) ) {
                                            $address .= $relatedNode->field_event_city['und'][0]['value'];
                                        }
                                        if ( !empty($relatedNode->field_event_state['und'][0]['value']) ) {
                                            if ( $address !== '' ) {
                                                $address .= ', ';
                                            }
                                            $address .= acronymToStateName($relatedNode->field_event_state['und'][0]['value']);
                                        }
                                        print $address;
                                    ?>
                                </div>
                                <div class="event-calendarlinks">
                                    <?php
                                        dsm($relatedNode);
                                        $nodeTitle = urlencode($relatedNode->title);
                                        $linkToNode = urlencode("http://business.usa.gov/node/{$relatedNode->nid}");
                                        
                                        $address = '';
                                        $address .= ( empty($relatedNode->field_event_address_1['und'][0]['value']) ? '' : $relatedNode->field_event_address_1['und'][0]['value'] . ', ' );
                                        $address .= ( empty($relatedNode->field_event_address_2 ['und'][0]['value']) ? '' : $relatedNode->field_event_address_2 ['und'][0]['value'] . ', ' );
                                        $address .= ( empty($relatedNode->field_event_city['und'][0]['value']) ? '' : $relatedNode->field_event_city['und'][0]['value'] . ', ' );
                                        $address .= ( empty($relatedNode->field_event_state['und'][0]['value']) ? '' : $relatedNode->field_event_state['und'][0]['value'] . ', ' );
                                        $address = urlencode($address);
                                        
                                        $snippet = strip_tags( $relatedNode->field_event_detail_desc['und'][0]['value'] );
                                        $snippet = trim( str_replace(array("\n","\r","\f", "\t"), "", $snippet) );
                                        $snippet = urlencode(truncate_utf8($snippet, 250, true, true));
                                        
                                        $eventsStartDt = new DateTime($relatedNode->field_event_date['und'][0]['value'] . ' UTC');
                                        $eventsEndDt = new DateTime($relatedNode->field_event_date['und'][1]['value'] . ' UTC');
                                        $gDates = $eventsStartDt->format("Ymd\\This") . 'Z/' . $eventsStartDt->format("Ymd\\This") . 'Z';
                                        $yDateStart = $eventsStartDt->format("Ymd\\This");
                                        $yDateEnd = $eventsStartDt->format("Ymd\\This");
                                        
                                        $googleCalLink = "https://www.google.com/calendar/render?action=TEMPLATE&text={$nodeTitle}&dates={$gDates}&sprop=website:{$linkToNode}&location={$address}&details={$snippet}";
                                        $yahooCalLink = "http://calendar.yahoo.com/?v=60&amp;TITLE={$nodeTitle}&ST={$yDateStart}&ET={$yDateEnd}&URL={$linkToNode}&in_loc={$address}&DESC={$snippet}";
                                    ?>
                                    <a href="/ical/<?php print $relatedNode->nid; ?>/calendar.ics" title="Export event to your Outlook calendar">
                                        <img class="ical" src="/sites/all/themes/bizusa/images/outlook_cal.png" />
                                    </a>
                                    <a href="<?php print $googleCalLink; ?>" title="Export event to your Google calendar">
                                        <img class="gcal" src="/sites/all/themes/bizusa/images/gcal.png" />
                                    </a>
                                    <a href="<?php print $yahooCalLink; ?>" title="Export event to your Yahoo calendar">
                                        <img class="ycal" src="/sites/all/themes/bizusa/images/ycal.png" />
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <?php if (!empty($content['last_date_modified'])): ?>
        <?php print render($content['last_date_modified']); ?>
    <?php endif; ?>    
    
</div>

