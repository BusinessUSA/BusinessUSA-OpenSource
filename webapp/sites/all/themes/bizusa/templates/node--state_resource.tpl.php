<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>" xmlns="http://www.w3.org/1999/html"></div>

    <!--  <h2>The following mark-up comes after CoderBookmark:CB-V1ILCQE-BC in <?php print basename(__FILE__); ?></h2>   -->
    <div class="state-resource-lib-maincontent">
      

        <!-- Description -->
        <div class="field-container description">
            <div class="field-value">
                <?php print ($node->body['und'][0]['value']); ?>
            </div>
        </div>


    <!-- Detail -->
    <?php if(!empty($node->field_state_resource_link['und'][0]['value'])): ?>
        <div class="field-container field-learn-more">
            <div class="field-label">Learn More: </div>
            <div class="field-value">
                <a href="<?php print ($node->field_state_resource_link['und'][0]['value']); ?>"  target="_blank">
                    <?php print ($node->field_state_resource_link['und'][0]['value']); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

