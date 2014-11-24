<?php ob_start(); ?>

<?php
    print theme(
            'yawizard_from_excel', 
            array(
                'path' => 'sites/all/pages/example-wizard-2/wizard-questions.xls',
                'resultsURL' => '/example-wizard-2/wizard-results'
            )
    );
?>

<?php
    if ( !empty($_GET['widget']) && intval($_GET['widget']) === 1 ) {
        global $overrideBodyMarkup;
        $overrideBodyMarkup = ob_get_contents();
        ob_end_clean();
    } else {
        ob_end_flush();
    }
?>
