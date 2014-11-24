<?php

$plugin = array(
  'form' => 'feeds_tamper_return_result_of_xpath_query_form',
  'callback' => 'feeds_tamper_return_result_of_xpath_query_callback',
  'name' => 'Set value to result of XPath query',
  'multi' => 'loop',
  'category' => 'Markup Tools and Manipulation',
);

function feeds_tamper_return_result_of_xpath_query_form($importer, $element_key, $settings) {

    $form = array();
    
    $form['welcomemsg'] = array(
        '#type' => 'markup',
        '#markup' => '
            <br/>
            This Feed Tamper expects to be given a string of HTML in which it shall parse.<br/>
            <br/>
            This Feed Tamper will execute the XPath query you give it, and write the result of this query into the target [Tamper] Field<br/>
            This can be used in may ways, like cropping HTML, or to set the field to a value found in a certain HTML-tag\'s propery, etc.<br/>
            <br/>
        '
    );
    
    $form['xpathmode'] = array(
        '#type' => 'radios',
        '#title' => 'Set the target [Tamper] field to',
        '#default_value' => 'pullprop',
        '#options' => array(
            'run' => 'The direct XML/HTML result of running an XPath query',
            'pullprop' => 'The value of a tag\'s property, the tag shall be found with the XPath query',
        )
    );
    if ( !empty($settings['xpathmode']) ) {
        $form['xpathmode']['#default_value'] = $settings['xpathmode'];
    }
    
    $form['xpathquery'] = array(
        '#type' => 'textfield',
        '#title' => 'XPath query',
        '#default_value' => '//a',
        '#description' => 'The given XPath query will be run against the input for this Feed Tamper, the result of this XPath query will be set to the target [Tamper] field. <br/>Please note that if your XPath query returns more than on result (an array), only the first element will be used.'
    );
    if ( !empty($settings['xpathquery']) ) {
        $form['xpathquery']['#default_value'] = $settings['xpathquery'];
    }
    
    $form['proptarget'] = array(
        '#type' => 'textfield',
        '#title' => 'Set the target [Tamper] field to the value found in the following property (of the [first] tag found in the above XPath query)',
        '#default_value' => 'href',
        '#states' => array(
            'visible' => array(
                'input[name="settings[xpathmode]"]' => array( 'value' => 'pullprop' )
            )
        )
    );
    if ( !empty($settings['proptarget']) ) {
        $form['proptarget']['#default_value'] = $settings['proptarget'];
    }
    
    return $form;
}

function feeds_tamper_return_result_of_xpath_query_callback($result, $item_key, $element_key, &$field, $settings, $source) {

    // Init HTML parsing
    $doc = new DOMDocument();
    @$doc->loadHTML($field);
    $xpath = new DomXPath($doc);
    
    // Execute xpath query
    $xpathQueryToFindTags = trim($settings['xpathquery']);
    $foundTags = $xpath->query($xpathQueryToFindTags);
    if ( intval($foundTags->length) === 0 ) {
        error_log("WARNING: feeds_tamper_return_result_of_xpath_query_callback() found no tags for the XPath query: " . $xpathQueryToFindTags);
    } else {
    
        // Foreach result found from running the XPath query
        foreach ( $foundTags as $foundTag ) {
        
            if ( $settings['xpathmode'] === 'run' ) {
                $field = $doc->saveHTML($foundTag);
            } elseif ( $settings['xpathmode'] === 'pullprop' ) {
                $propertyToGetValueOf = $settings['proptarget'];
                $field = $foundTag->getAttribute($propertyToGetValueOf);
            }
            
            break;
        }
    }
    
}
