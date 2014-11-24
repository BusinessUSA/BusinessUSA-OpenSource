<div class="wizard-result-sections-container" style="background-color: white; padding: 10px; min-height: 350px;">
    Hello, and welcome to this wizard's result slide!<br/>
    <br/>
    This markup is rendered from: <?php print __FILE__; ?><br/>
    You can review that file to see how the following is processed:<br/>
    <hr/>
    <b>In the previous slides, you stated that:</b> <br/>
    <?php if ( intval($_REQUEST['inputs']['pet_yes']) === 0 ): ?>
        You do not have any pet.
    <?php else: ?>
        You have 
        
        <?php if ( intval($_REQUEST['inputs']['pickanimal_dog']) === 1 ): ?>
            <?php if ( intval($_REQUEST['inputs']['pickanimal_dog_big']) === 1 ): ?>
                a big dog, 
            <?php endif; ?>
            <?php if ( intval($_REQUEST['inputs']['pickanimal_dog_small']) === 1 ): ?>
                a small dog, 
            <?php endif; ?>
            <?php if ( intval($_REQUEST['inputs']['pickanimal_dog_big']) === 0 && intval($_REQUEST['inputs']['pickanimal_dog_small']) === 0 ): ?>
                a dog, 
            <?php endif; ?>
            
        <?php endif; ?>
        
        <?php if ( intval($_REQUEST['inputs']['pickanimal_cat']) === 1 ): ?>
            <?php if ( intval($_REQUEST['inputs']['pickanimal_cat_long']) === 1 ): ?>
                a short-haired cat, 
            <?php endif; ?>
            <?php if ( intval($_REQUEST['inputs']['pickanimal_cat_short']) === 1 ): ?>
                a long-haired cat, 
            <?php endif; ?>
            <?php if ( intval($_REQUEST['inputs']['pickanimal_cat_long']) === 0 && intval($_REQUEST['inputs']['pickanimal_cat_short']) === 0 ): ?>
                a cat, 
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if ( intval($_REQUEST['inputs']['pickanimal_other']) === 1 ): ?>
            <?php if ( trim($_REQUEST['inputs']['pickanimal_txt_other']) !== "" ): ?>
                a <?php print $_REQUEST['inputs']['pickanimal_txt_other']; ?>, 
            <?php else: ?>
                some other animal, 
            <?php endif; ?>
        <?php endif; ?>
        
        as pets.
        
    <?php endif; ?>
    <hr/>
    This <?php print basename(__FILE__); ?> script can tell what options you selected based on the HTTP-POST information 
    sent [in the AJAX request] when requesting the URL: <?php print request_uri(); ?><br/>
    <br/>
    The HTTP-POST data sent in this request was:<br/>
    <pre><?php print var_export($_REQUEST, true); ?></pre>
</div>
