<script>
    jQuery.extend(yawizard, <?php print json_encode($variables); ?>);
    jQuery(document).ready( function () {
        jQuery("#titleWrapper").hide();
        yawizard.init();

        jQuery(".slide-id-0 .splash-icon-image, .slide-id-0 .splash-title").wrapAll("<div class='titleWrap'></div>");
    });

</script>

<!-- The following markup is generated from yawizard.tpl.php -->
<a class="wizard-top" name="wizard-top"></a>
<div class="wrapper wizard-has-outside-sidebar-<?php print ( $variables['sideBars'] === false ? 'false' : 'true' ); ?>">

    <form class="wizard wizard-container wizard-uniqueid-<?php print $variables['uniqueId']; ?>" action="javascript: yawizard.next(); void(0);">
        <div class="wizardHeader">
            <h1 class="wizard-title">
                <a href="javascript: yawizard.seek(0); void(0);">
                    <?php print $variables['title']; ?>
                </a>
            </h1>
            <div class="tabs slides-shown-count-<?php print count($variables['slides']); ?>">
                <a class="wizard-back" href="javascript: yawizard.back();">Back</a>
                <div class="slide-selector-container">
                    <ul class="slide-selector slide-count-<?php print count($variables['slides']); ?>">
                        <?php foreach ( $variables['slides'] as $slideIndex => $slide ): ?>
                            <li class="slide-selector-<?php print $slideIndex; ?>">
                                <a href="javascript: yawizard.seek(<?php print $slideIndex; ?>);">
                                    <?php print $slide['title']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a class="wizard-next" href="javascript: yawizard.next();">Next</a>
            </div>
        </div>
        <?php $questionNumericalID = 1; ?>
        <?php foreach ( $variables['slides'] as $slideId => $slide ): ?>
            <div class="slide slide-id-<?php print $slideId; ?>"  style="display: none; <?php print ( empty($slide['clipart']) ? '' : "background-image: url({$slide['clipart']});" ); ?>" note="both background-image and background-color may be set as an inline style on this div from the excel spreadsheet">
                <?php if($slideId === 0): //we only want share to show on the splash, assuming splash is always 0 ?>
                    <?php print theme('sharewidget', array()); ?>
                <?php endif;?>
                <div class="questions">
                    <?php foreach ( $slide['questions'] as $questionId => $question ): ?>
                        <div class="question-container widget-type-<?php print $question['widgetType']; ?> wiztag-<?php print $question['wizardTag']; ?> <?php print $question['containerClass'] ?>" questionNumericalID="<?php print $questionNumericalID++; ?>" wiztag="<?php print $question['wizardTag']; ?>">
                            <?php print theme('yawizard_question', $question); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="ajax-container ajax-unused" style="display: none;">
                    <!-- This div will all always remain hidden, and only shown if/when this slide is set to pull its content from an external source -->
                    <?php print theme('yawizard_ajax_loading_spinner'); ?>
                </div>
                <a class="wizard-continue" href="javascript: yawizard.next();">Continue</a>
            </div>
        <?php endforeach; ?>
        
    </form>

    <?php if ( $variables['sideBars'] !== false ): ?>
        <div class="wizard-outside-sidebars-container">
            <?php foreach ( $variables['sideBars'] as $index => $sideBar ): ?>
                <div class="wizard-outside-sidebar wizard-outside-sidebar-<?php print $index; ?>" >
                    <?php include($sideBar); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
</div>