<?php
    
    $wizardQuestions = ya_wizard_WizardFromExcel($variables['path']);
    $lastSlideId = count( $wizardQuestions['slides'] ) - 1;
    $wizardQuestions['slides'][$lastSlideId]['ajaxLoad'] = $variables['resultsURL'];
    
    print theme('yawizard', array_merge($variables, $wizardQuestions));