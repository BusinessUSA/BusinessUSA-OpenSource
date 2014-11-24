<?php

/**
 * @file
 * Given HTML for this field, alter all anchors/images 
 */

$plugin = array(
  'form' => 'feeds_tamper_delete_tags_by_xpath_form',
  'callback' => 'feeds_tamper_delete_tags_by_xpath_callback',
  'name' => 'Delete tag element(s) found with XPath',
  'multi' => 'loop',
  'category' => 'Markup Tools and Manipulation',
);

function feeds_tamper_delete_tags_by_xpath_form($importer, $element_key, $settings) {

    $form = array();

    $form['welcomemsg'] = array(
        '#type' => 'markup',
        '#markup' => '
            <br/>
            This Feed Tamper expects to be given a string of HTML in which it shall parse.<br/>
            <br/>
            This Feed Tamper will remove any tag(s)/element(s) found for a given XPath query.<br/>
            <br/>
        '
    );
    
    $form['xpath'] = array(
        '#type' => 'textfield',
        '#title' => 'Delete XML/HTML element(s) found with the XPath query:',
        '#default_value' => '//div[@class="class-i-dont-like"]',
    );
    if ( !empty($settings['xpath']) ) {
        $form['xpath']['#default_value'] = $settings['xpath'];
    }
    
    $form['onlyfirst'] = array(
        '#title' => "Only delete the first element found",
        '#type' => 'checkbox',
        '#description' => 'If this checkbox is not ticked, and your XPath-query finds multiple element, they will all be deleted. <br/>If this checkbox is ticked, only the first element found by the XPath-query will be deleted.',
    );
    if ( !empty($settings['onlyfirst']) ) {
        $form['onlyfirst']['#default_value'] = $settings['onlyfirst'];
    }
    
    return $form;
}

function feeds_tamper_delete_tags_by_xpath_callback($result, $item_key, $element_key, &$field, $settings, $source) {
    
    // Initialize HTML parsing and execute XPath query
    $doc = new DOMDocument();
    @$doc->loadHTML($field);
    $xpath = new DomXPath($doc);
    $xpathQuery = trim($settings['xpath']);
    $foundTags = $xpath->query($xpathQuery);

    // Verify we found element(s)
    if ( intval($foundTags->length) === 0 ) {
        error_log("WARNING: feeds_tamper_delete_tags_by_xpath_callback: No tags found for the XPath query: " . $xpathQuery);
        return;
    }
    
    foreach ( $foundTags as $foundTag ) {
        
        $foundTag->parentNode->removeChild($foundTag);
        
        if ( intval($settings['onlyfirst']) === 1 ) {
            break;
        }
    }
    
    $field = $doc->saveHTML();
    
}

