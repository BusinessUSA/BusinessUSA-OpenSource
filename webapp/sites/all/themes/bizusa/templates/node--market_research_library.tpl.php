<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>" xmlns="http://www.w3.org/1999/html">

    <!--  <h2>The following mark-up comes after CoderBookmark:CB-V1ILCQE-BC in <?php print basename(__FILE__); ?></h2>   -->
    <div class="Market-research-lib-maincontent">
    
            <!-- Detail -->
            <div class="field-container detail-field">
                <div class="field-label">Description :</div>
                <div class="field-value">
                    <?php print ($node->field_mrlib_description['und'][0]['value']); ?>
                </div>
            </div>
            
            <!--  Expiration Date  -->
            <?php if( !empty($node->field_mrlib_expiration_date['und'][0]['value'])): ?>
                <div class="field-container expiration-date-field">
                    <div class="field-label">Expiration Date: </div>
                    <div class="field-value">
                        <?php
                            $dateString = $node->field_mrlib_expiration_date['und'][0]['value'];
                            $dt = new DateTime($dateString);
                            print  $dt->format('m/d/Y');
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!--  Reporting Type -->
            <?php if(!empty($node->field_mrlib_report_type['und'][0]['value'])): ?>
                <div class="field-container reporting-type-field">
                    <div class="field-label">Reporting Type: </div>
                    <div class="field-value">
                        <?php print ($node->field_mrlib_report_type['und'][0]['value']); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!--  Country  -->
            <?php if(count ($node->field_mrlib_country['und']) > 0 ): ?>
                 <div class="field-container field-country">
                    <div class="field-label">Country: </div>
                    <div class="field-value">
                        <?php  foreach( $node->field_mrlib_country['und'] as $country ): ?>
                             <?php if ( count ($node->field_country['und']) > 1 ) { print ","; } ?>
                             <?php print acronymToCountryName($country['value']); ?>
                        <?php endforeach; ?>
                    </div>
                 </div>
            <?php endif; ?>

            <!--  Learn More URL  -->
            <?php if(!empty($node->field_mrlib_learn_more_url['und'][0]['value'])): ?>
                <div class="field-container field-learn-more">
                    <div class="field-label">Learn More: </div>
                    <div class="field-value">
                        <a href="<?php print ($node->field_mrlib_learn_more_url['und'][0]['value']); ?>"  target="_blank">
                            <?php print ($node->field_mrlib_learn_more_url['und'][0]['value']); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($content['last_date_modified'])): ?>
                <?php print render($content['last_date_modified']); ?>
            <?php endif; ?>
     </div>
     
 </div>

