<script rendersource="<?php print basename(__FILE__); ?>" <?php print $variables['containerAttribs']; ?>>
    var <?php print $variables['uniqVar']; ?>_counter = 0;
    var <?php print $variables['uniqVar']; ?> = [];
    <?php for ( $page = 0 ; $page < $variables['pageCount'] ; $page++ ): ?>
        
        <?php print $variables['uniqVar']; ?>.push(<?php 
            print json_encode(
                theme(
                    'yawizard_pagedresults_page',
                    array(
                        'uniqVar' => $variables['uniqVar'],
                        'pageId' => ( $page + 1 ),
                        'resultMarkups' => array_slice($variables['resultMarkups'], $page * $variables['resultsPerPage'], $variables['resultsPerPage']),
                        'resultCount' => $variables['resultCount'],
                        'resultsPerPage' => $variables['resultsPerPage'],
                        'pageCount' => $variables['pageCount']
                    )
                )
            ); 
        ?>);
    <?php endfor; ?>
    
    function  <?php print $uniqVar; ?>_hook_pagechange() {
    
        // Check if there is a yaPager_hooks_pagechange() function/array defined somewhere
        if ( typeof yaPager_hooks_pagechange == 'undefined' ) {
            return;
        } else {
            
            // If yaPager_hooks_pagechange is a function, call it 
            if ( typeof yaPager_hooks_pagechange == 'function' ) {
                yaPager_hooks_pagechange( jQuery('#<?php print $variables['uniqVar']; ?>'), <?php print $variables['uniqVar']; ?>_counter);
            }
            
            // If yaPager_hooks_pagechange is an array, assume it is an array of functions, and call them all
            if ( typeof yaPager_hooks_pagechange == 'object' ) {
                for ( var x = 0 ; x < yaPager_hooks_pagechange.length ; x++ ) {
                    yaPager_hooks_pagechange[x]( jQuery('#<?php print $variables['uniqVar']; ?>'), <?php print $variables['uniqVar']; ?>_counter);
                }
            }

            Drupal.attachBehaviors($('.yawizard-pagedresults-mastercontainer'), Drupal.settings);
        }
    }
    
</script>
<div id="<?php print $variables['uniqVar']; ?>" class="yawizard-pagedresults-mastercontainer" rendersource="<?php print basename(__FILE__); ?>" <?php print $variables['containerAttribs']; ?>>
    <?php 
        print theme(
            'yawizard_pagedresults_page',
            array(
                'uniqVar' => $variables['uniqVar'],
                'pageId' => 1,
                'resultMarkups' => array_slice($variables['resultMarkups'], 0, $variables['resultsPerPage']),
                'resultCount' => $variables['resultCount'],
                'resultsPerPage' => $variables['resultsPerPage'],
                'pageCount' => $variables['pageCount']
            )
        );
    ?>
</div>
