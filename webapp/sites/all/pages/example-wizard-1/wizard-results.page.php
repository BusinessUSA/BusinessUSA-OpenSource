<div class="wizard-result-sections-container" style="background-color: white; padding: 10px; min-height: 350px;">
    Hello, and welcome to this wizard's result slide!<br/>
    <br/>
    This markup is rendered from: <?php print __FILE__; ?><br/>
    You can review that file to see how the following is processed:<br/>
    <hr/>
    <b>In the previous slides, you selected the colors:</b> 
    <?php
        if ( $_REQUEST['inputs']['pickcolor_red'] ) {
            print 'red, ';
        }
        if ( $_REQUEST['inputs']['pickcolor_green'] ) {
            print 'green, ';
        }
        if ( $_REQUEST['inputs']['pickcolor_blue'] ) {
            print 'blue, ';
        }
    ?><br/>
    <br/>
    <b>In the previous slides, you selected the animals:</b> 
    <?php
        if ( $_REQUEST['inputs']['pickanimal_dog'] ) {
            print 'dog, ';
        }
        if ( $_REQUEST['inputs']['pickanimal_cat'] ) {
            print 'cat, ';
        }
        if ( $_REQUEST['inputs']['pickanimal_fish'] ) {
            print 'fish, ';
        }
        if ( $_REQUEST['inputs']['pickanimal_hamster'] ) {
            print 'hamster, ';
        }
        if ( $_REQUEST['inputs']['pickanimal_horse'] ) {
            print 'horse, ';
        }
    ?><br/>
    <br/>
    This <?php print basename(__FILE__); ?> script can tell what options you selected based on the HTTP-POST information 
    sent [in the AJAX request] when requesting the URL: <?php print request_uri(); ?><br/>
    <br/>
    The HTTP-POST data sent in this request was:<br/>
    <pre><?php print var_export($_REQUEST, true); ?></pre>
</div>
