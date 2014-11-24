<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>" xmlns="http://www.w3.org/1999/html">
</div>
<?php
    /**
     * Created by PhpStorm.
     * User: naga.tejaswini
     * Date: 9/18/14
     * Time: 1:33 PM
     */
    kprint_r( $node );
?>

<div class="country-com-guide-maincontent">
        <label class="lblclass">Description :</label>
        <?php print ($node->field_ccguide_description['und'][0]['value']); ?> <br/>
<!--  Expiration Date  -->

<?php if(!empty($node->field_ccguide_expiration_date['und'][0]['value']))
{?>
    <div class="expiration-date-field">
        <b><label class="lblclass">Expiration Date : </label></b>
        <?php
        $dateString = $node->field_ccguide_expiration_date['und'][0]['value'];
        $dt = new DateTime($dateString);
        print  $dt->format('m/d/Y');
        ?><br/>
    </div>
<?php }?>

<!--  Reporting Type -->
<?php if(!empty($node->field_ccguide_report_type['und'][0]['value']))
{?>
    <div class="reporting-type-field">
        <b><label class="lblclass">Reporting Type : </label></b>
        <?php print ($node->field_ccguide_report_type['und'][0]['value']); ?><br/>
    </div>
<?php }?>

<!--  Country  -->
<?php if(count ($node->field_ccguide_countries['und']) > 0 )
{  ?>
    <div class=class="field-country">
        <b><label class="lblclass">Country : </label></b>
        <?php  foreach($node->field_ccguide_countries['und'] as $country)
        { ?>
            <?php print acronymToCountryName($country['value']); ?>
            <?php if(count ($node->field_ccguide_countries['und']) > 1 )
             {
                 print ",";
             } ?>
        <?php }?>
    </div>
<?php } ?>

<!--  Learn More URL  -->
<?php if(!empty($node->field_ccguide_learn_more_url['und'][0]['value']))
{?>
    <div class="field-learn-more">
        <b><label class="lblclass">Learn More : </label></b>
        <a href="<?php print ($node->field_ccguide_learn_more_url['und'][0]['value']); ?>"  target="_blank">
            <?php print ($node->field_ccguide_learn_more_url['und'][0]['value']); ?> </a> <br/>
    </div>
<?php }?>
        
        <?php if (!empty($content['last_date_modified'])): ?>
            <?php print render($content['last_date_modified']); ?>
        <?php endif; ?>
</div>