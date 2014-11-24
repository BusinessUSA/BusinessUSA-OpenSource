<?php
    
  //  dsm( $_POST );


require_once('sites/all/themes/bizusa/templates/recaptchalib.php');
$privatekey = "6LeYk_ASAAAAAG2PknwOXiv_1YP9k39Ir0YEAWxs";



$resp = recaptcha_check_answer ($privatekey,
    $_SERVER["REMOTE_ADDR"],
    $_POST["recaptcha_challenge_field"],
    $_POST["recaptcha_response_field"]);

//var_dump($resp);
if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
   /* die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
        "(reCAPTCHA said: " . $resp->error . ")");*/
    print "<div class='vendorLightBox'>Please review your submission and try again</div>";
} else
    // Your code here to handle a successful verification


{
    $targetNodeId = $_POST['nid'];
    $targetNode = node_load($targetNodeId);
    
    // Get the vocabulary ID - We shall EXPECT that this vocabulary already exists in our system
    $vid = db_query("SELECT vid FROM taxonomy_vocabulary WHERE machine_name = 'user_submittted_tags'")->fetchField();
    // Get the [parent]-term's ID for all terms which shall be submitted by users - we EXPECT this ("User Submitted Term") term to exist in this vocabulary
    $parentTid = db_query("SELECT tid FROM taxonomy_term_data WHERE name='User Submitted Term' AND vid={$vid}")->fetchField();
    
    $userInputTags = strval( $_POST['tags'] );
    $userInputTags = explode(',', $userInputTags);
    dsm( $userInputTags );
    foreach ( $userInputTags as $userInputTag ) {

        $termAlreadyExistsWithTidOf = db_query("SELECT tid FROM taxonomy_term_data WHERE name='{$userInputTag}' LIMIT 1")->fetchField();
        if ( $termAlreadyExistsWithTidOf === false ) {
            // Create the term.
            $newTerm = (object) array(
                'name' => $userInputTag,
                'vid' => $vid,
                'parent' => array($parentTid),
            );
            taxonomy_term_save($newTerm); // [!!] WARNING [!!] - taxonomy_term_save() WILL create duplicate taxonomy terms, ALWAYS check if a taxonomy terms with the same titles exists before calling this fucntion
            
            //dsm("I have just created a NEW taxonomy term called '{$userInputTag}' with term-id {$parentTid} as the parent. The new term-id is {$newTerm->tid} ");
            $termIdToAssign = $newTerm->tid;
        } else {
            //dsm("I am NOT creating a duplicate taxonomy term for '{$userInputTag}', it already exists with the term-id of {$termAlreadyExistsWithTidOf} ");
            $termIdToAssign = $termAlreadyExistsWithTidOf;
        }
        
        if ( empty($targetNode->field_pending_tags) || !is_array($targetNode->field_pending_tags) ) {
            $targetNode->field_pending_tags = array( 'und' => array() );
        }
        
        $targetNode->field_pending_tags['und'][] = array(
            'tid' => $termIdToAssign
        );
    }
    

    node_save( $targetNode );

    print "<div class='vendorLightBox'>Thank you for your submission. The tag is submitted sucessfully for review.</div>";
  }
?>




