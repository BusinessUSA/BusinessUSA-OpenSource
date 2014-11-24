<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>" xmlns="http://www.w3.org/1999/html"></div>

<?php
//dsm( $node );

?>

<!--  <h2>The following markup comes after CoderBookmark:CB-V1ILCQE-BC in <?php print basename(__FILE__); ?></h2>   -->
<div class="tradeleads-maincontent">
    
    <br>
    
    <ul class="list-group">

		<!-- test -->
        <!--  Project Status  -->
        <?php if(!empty($node->field_tradelead_status['und'][0]['value'])) {?>
            <li class="list-group-item field-project-status">
                <label>Project Status : </label>
                <?php print ($node->field_tradelead_status['und'][0]['value']); ?>
            </li>
        <?php }?>

        <!--  Publish Date  -->
        <?php if(!empty($node->field_tradelead_publish_date['und'][0]['value']))
        {?>
            <li class="list-group-item field-publish-date">
                <label>Publish Date : </label>
                <?php
                    $dateString = $node->field_tradelead_publish_date['und'][0]['value'];
                    $dt = new DateTime($dateString);
                    print  $dt->format('m/d/Y');
                ?>
            </li>
        <?php }?>

        <!--  End Date  -->
        <?php if(!empty($node->field_end_date['und'][0]['value']))
        {?>
            <li class="list-group-item field-end-date">
                <label>End Date : </label>
                <?php
                    $dateString = $node->field_end_date['und'][0]['value'];
                    $dt = new DateTime($dateString);
                    print  $dt->format('m/d/Y');
                ?>
            </li>
        <?php }?>

    </ul>

    <ul class="list-group">

        <!--  Country  -->

        <?php if(count ($node->field_country['und']) > 0 )
        {  ?>
           <li class="list-group-item field-country">
                <label>Country : </label>
                <?php  foreach($node->field_country['und'] as $country)
                { ?>
                    <?php print acronymToCountryName($country['value']); ?>

                   <?php if(count ($node->field_country['und']) > 1 )
                        {
                        print ",";
                        } ?>
                <?php }?>
           </li>
        <?php } ?>

        <!--  Industry  -->
        <?php if(count($node->field_tradelead_industry['und']) > 0)
        {?>
            <li class="list-group-item field-industry">
                <label>Industry : </label>
                <?php  foreach($node->field_tradelead_industry['und'] as $industry)
                { ?>
                <?php print $industry['value']; ?>
                    <?php if(count ($node->field_tradelead_industry['und']) > 1 )
                       {
                        print ",";
                       } ?>
                <?php }?>
            </li>
        <?php }?>

    </ul>

    <ul class="list-group">
    
        <!--  Description  -->
        <li class="list-group-item">
            <label>Description :</label><br>
            <?php print ($node->field_description['und'][0]['value']); ?>
        </li>

    </ul>

    <ul class="list-group">
        <!--  Project Size  -->
        <?php if(!empty($node->field__tradelead_project_size['und'][0]['value']))
        {?>
            <li class="list-group-item field-project-size">
                <label>Project Size : </label>
                <?php print number_format(($node->field__tradelead_project_size['und'][0]['value']), 2, '.', ','); ?>
            </li>
        <?php }?>

        <!--  Funding Source  -->
        <?php if(!empty($node->field_funding_source['und'][0]['value']))
        {?>
            <li class="list-group-item field-funding-source">
                <label>Funding Source : </label>
                <?php print ($node->field_funding_source['und'][0]['value']); ?>
            </li>
        <?php }?>

        <!--  Source  -->
        <?php if(!empty($node->field_tradelead_source['und'][0]['value']))
        {?>
            <li class="list-group-item field-source">
                <label>Source : </label>
                <?php print ($node->field_tradelead_source['und'][0]['value']); ?>
            </li>
        <?php }?>

        <!-- Borrowing Entity -->
        <?php if(!empty($node->field_implementing_entity['und'][0]['value']))
        {?>
            <li class="list-group-item implement-borrow-entity-field">
                <label>Borrowing Entity : </label>

                <?php print ($node->field_implementing_entity['und'][0]['value']); ?>

                <?php if(!empty($node->field_borrowing_entity['und'][0]['value'])) { ?>
                    <?php print ($node->field_borrowing_entity['und'][0]['value']); ?>
                <?php } ?>
            </li>
       <?php }?>
        
        <!--  Procurement Organization  -->
        <?php if(!empty($node->field_tradelead_pr_org['und'][0]['value']))
        {?>
            <li class="list-group-item field-procurement-org">
                <label>Procurement organization : </label>
                <?php print ($node->field_tradelead_pr_org['und'][0]['value']); ?>
            </li>
        <?php }?>

    </ul>


    <ul class="list-group">
        <!--  Project Number  -->
        <?php if(!empty($node->field_tradelead_project_number['und'][0]['value']))
        {?>
            <li class="list-group-item field-project-number">
                <label>Project Number : </label>
                <?php print ($node->field_tradelead_project_number['und'][0]['value']); ?>
            </li>
        <?php }?>

        <!--  Contact  -->
        <li class="list-group-item field-source">

            <?php if(!empty($node->field_tradelead_proj_poc['und'][0]['value']) ) {?>
                <label>Contact : </label>
                <?php print ($node->field_tradelead_proj_poc['und'][0]['value']); ?>
            <?php } else { ?>
                <label>Contact : </label>
                <?php print ($node->field_contact['und'][0]['value']); ?>
            <?php }?>

        </li>

        <!--  Learn More URL  -->
        <?php if(!empty($node->field_tradelead_learn_more['und'][0]['value']))
        {?>
            <li class="list-group-item field-learn-more">
                <label>Learn More : </label>
                <a href="<?php print ($node->field_tradelead_learn_more['und'][0]['value']); ?>"  target="_blank">
                <?php print ($node->field_tradelead_learn_more['und'][0]['value']); ?> </a> 
            </li>
        <?php }?>

        <!--  PDF Links  -->
        <?php if( count($node->field_tradelead_mul_url['und']) > 0 )
        {?>
            <li class="list-group-item field-pdf">
                <label>PDF Links : </label>
                <?php $links = $node->field_tradelead_mul_url['und'];
                     dsm($links);
                     foreach ($links as $link) { ?>
                     <a href="<?php print $link['value'];?>" target="_blank"> <?php print $link['value'];?> </a>
                 <?php  } ?>
            </li>
        <?php }?>
        
        <!--  Notice Type  -->
        <?php if(!empty($node->field_tradelead_notice_type['und'][0]['value'])) {?>
            <li class="list-group-item field-notice-type">
                <label>Notice Type : </label>
                <?php print ($node->field_tradelead_notice_type['und'][0]['value']); ?>
            </li>
        <?php }?>
    
    </ul>

		<?php if (!empty($content['last_date_modified'])): ?>
				<?php print render($content['last_date_modified']); ?>
		<?php endif; ?>    		
</div>