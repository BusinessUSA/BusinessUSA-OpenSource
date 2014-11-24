<?php
    $suggested_results = array();
    $default_suggested_result_color = '#359AB7';

    // Note: This function is defined in WizardLoader.php
    $wizards = findRelatedWizardsToSearchTerms($variables['search']);
    // Process Wizards and add the suggested_results.
    foreach ($wizards as $wizard ) {
      $wizardCountInResults++;
      $wizardSwimlaneLink = '/' . $wizard['field_swimlane_wizurl_value'];
      // Determin the color associated with this wizard
      if ( !empty($wizard['field_swimlane_color_value']) ) {
        $bgColor = '#' . ltrim($wizard['field_swimlane_color_value'], '#'); // ensure this color-codes starts with a single # character.
      }
      else {
        $bgColor = $default_suggested_result_color;
      }

      if ( !empty($wizard['field_search_icon_override_url']) ) {
        $suggested_results[] = array(
          'nid' => $wizard['nid'],
          'bgcolor' => $bgColor,
          'iconURL' => $wizard['field_search_icon_override_url'],
          'title' => $wizard['title'],
          'snippet' => $wizard['field_search_snippet_override_value'],
          'url' => $wizardSwimlaneLink,
        );
      }
    }

?>

<div class="searchresults-topwizardarea-container wizard-result-count-<?php print count($wizards); ?>">
    <?php foreach( $suggested_results as $result ) { ?>


            <?php /* Only show this result if there is an icon for it... @TODO: This needs to be refactored at a later time. */ ?>

                <div class="searchresults-topwizardarea-wizarditem searchresults-topwizardarea-wizarditem-<?php print cssFriendlyString($result['title']); ?>" wizardlink="/" onclick=" window.open('<?php print $result['url'] ?>');">
                    <div class="searchresults-topwizardarea-wizarditem-colorspacer" style="background-color: <?php print $result['bgcolor']; ?>;">

                        <!-- This div is only meant to be used as a color-bar -->

                        <div class="searchresults-topwizardarea-wizarditem-leftright-container">
                            <div class="searchresults-topwizardarea-wizarditem-leftside">
                                <!-- Result Node ID is: <?php print $result['nid']; ?> -->

                                <div class="searchresults-topwizardarea-wizarditem-icon">
                                  <img src="<?php print $result['iconURL']; ?>" class="search-result-icon-img" alt="Icon for this wizard result" note="This img is rendered from <?php print basename(__FILE__); ?>">
                                </div>

                                <div class="searchresults-topwizardarea-wizarditem-title">
                                    <a href="javascript: void(0);"><?php print $result['title']; ?></a>
                                </div>
                            </div>
                            <div class="searchresults-topwizardarea-wizarditem-rightside">
                                <div class="searchresults-topwizardarea-wizarditem-snippet">
                                    <?php print $result['snippet']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <?php } ?>
</div>
