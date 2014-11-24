<?php

/**
 * @file
 * Given HTML for this field, alter all anchors/images 
 */

$plugin = array(
  'form' => 'feeds_tamper_absolute_links_imgs_in_markup_form',
  'callback' => 'feeds_tamper_absolute_links_imgs_in_markup_callback',
  'name' => 'Force img/a absolute targets',
  'multi' => 'loop',
  'category' => 'Markup Tools and Manipulation',
);

function feeds_tamper_absolute_links_imgs_in_markup_form($importer, $element_key, $settings) {
    $form = array();
    
    $form['alterlinks'] = array(
        '#title' => "Alter href properties in anchor (&lt;a&gt;) tags",
        '#type' => 'checkbox'
    );
    if ( !empty($settings['alterlinks']) ) {
        $form['alterlinks']['#default_value'] = $settings['alterlinks'];
    }
    
    $form['alterimages'] = array(
        '#title' => "Alter src properties in images (&lt;img&gt;) tags",
        '#type' => 'checkbox'
    );
    if ( !empty($settings['alterimages']) ) {
        $form['alterimages']['#default_value'] = $settings['alterimages'];
    }
    
    $form['limitcontext'] = array(
        '#type' => 'textfield',
        '#title' => 'Only alter images and/or links within this XPath context',
        '#default_value' => '//',
        '#description' => 'Only [a/img]-tags within the container(s) found at this given XPath will be altered. Leave as // or blank to alter all a/img tags.<br/><b>WARNING:</b> If a context is given, and no element(s) is found for the given XPath (context), then nothing will be altered.',
    );
    if ( !empty($settings['limitcontext']) ) {
        $form['limitcontext']['#default_value'] = $settings['limitcontext'];
    }
    
    $form['absolutedomain'] = array(
        '#type' => 'textfield',
        '#title' => 'Translate <i>relative</i> links/img-sources to absolute-targets, assuming their source domain is',
        '#default_value' => 'http://dalefrey.net/',
        '#description' => 'All images and/or anchors will have their href/src properties set to an absolute path, startting with this string.<br/>Links or image-sources which are already absolute paths will not be effected by this process.',
    );
    if ( !empty($settings['absolutedomain']) ) {
        $form['absolutedomain']['#default_value'] = $settings['absolutedomain'];
    }
    
    $form['debugging'] = array(
        '#title' => "Send debugging messages with verbosity to php's error log",
        '#type' => 'checkbox',
        '#description' => 'With this checkbox ticked, error_log() will be called to report what changes were or were not made durring the execution of this process.',
    );
    if ( !empty($settings['debugging']) ) {
        $form['debugging']['#default_value'] = $settings['debugging'];
    }
    
    return $form;
}

function feeds_tamper_absolute_links_imgs_in_markup_callback($result, $item_key, $element_key, &$field, $settings, $source) {

    // Debugging and verbosity
    $writeDebugMsgs = intval($settings['debugging']);
    if ( $writeDebugMsgs ) {
        error_log("feeds_tamper_absolute_links_imgs_in_markup_callback has been invoked with the following settings: " . print_r($settings, true));
        error_log("and it will tamper with the following value: " . $field);
    }
    
    // Initialize HTML parsing
    $doc = new DOMDocument();
    @$doc->loadHTML($field);
    $xpath = new DomXPath($doc);
    $queryBaseXPath = trim($settings['limitcontext']);
    
    // Ensure the context ($queryBaseXPath) starts and ends with a //
    $queryBaseXPath = trim($queryBaseXPath, '/');
    $queryBaseXPath = trim($queryBaseXPath, '/');
    $queryBaseXPath = '//' . $queryBaseXPath . '//';
    
    if ( intval($settings['alterlinks']) === 0 ) {
        
        if ( $writeDebugMsgs ) {
            error_log("feeds_tamper_absolute_links_imgs_in_markup_callback will not alter any anchors as per the configuration.");
        }
        
    } else {
    
        $xpathQueryToFindTags = $queryBaseXPath . 'a';
        if ( $writeDebugMsgs ) { error_log("The following query will be used to find target anchors: " . $xpathQueryToFindTags); }
        
        // Search for any anchors within this area
        $foundTags = $xpath->query($xpathQueryToFindTags);
        if ( intval($foundTags->length) === 0 ) {
            if ( $writeDebugMsgs ) {
                error_log("WARNING: No tags found for the XPath query: " . $xpathQueryToFindTags);
            }
        } else {
            foreach ( $foundTags as $foundTag ) {
                
                // Get the absolute path
                $newValue = absolute_links_imgs_in_markup_getLinkAbsolutePath(
                    $settings['absolutedomain'], 
                    $foundTag->getAttribute('href')
                );
                
                // Debugging reportting
                if ( $writeDebugMsgs ) {
                    error_log("Changed an anchors href property-value from " . $foundTag->getAttribute('href') . " to " . $newValue);
                }
                
                // Set the new value for this property on this tag
                $foundTag->setAttribute('href', $newValue); 
            }
        }
        
    }
    
    if ( intval($settings['alterimages']) === 0 ) {
    
        if ( $writeDebugMsgs ) {
            error_log("feeds_tamper_absolute_links_imgs_in_markup_callback will not alter any images as per the configuration.");
        }
        
    } else {
    
        $xpathQueryToFindTags = $queryBaseXPath . 'img';
        if ( $writeDebugMsgs ) { error_log("The following query will be used to find target images: " . $xpathQueryToFindTags); }
        
        // Search for any anchors within this area
        $foundTags = $xpath->query($xpathQueryToFindTags);
        if ( intval($foundTags->length) === 0 ) {
            if ( $writeDebugMsgs ) {
                error_log("WARNING: No tags found for the XPath query: " . $xpathQueryToFindTags);
            }
        } else {
            foreach ( $foundTags as $foundTag ) {
                
                // Get the absolute path
                $newValue = absolute_links_imgs_in_markup_getLinkAbsolutePath(
                    $settings['absolutedomain'], 
                    $foundTag->getAttribute('src')
                );
                
                // Debugging reportting
                if ( $writeDebugMsgs ) {
                    error_log("Changed an anchors src property-value from " . $foundTag->getAttribute('src') . " to " . $newValue);
                }
                
                // Set the new value for this property on this tag
                $foundTag->setAttribute('src', $newValue); 
            }
        }
    }
    
    // Compile markup
    $newFieldValue = $doc->saveHTML();
    
    // Debugging for the returned value for this Tamper
    if ( $writeDebugMsgs ) {
        if ( $field === false ) {
            error_log("ERROR - Could not compile HTML from the given input!");
        } else {
            error_log("The final result for this tamper is: " . $newFieldValue);
        }
    }
    
    // Update this field's value (Tamper), if there was no error in the HTML-conversion process
    if ( $newFieldValue !== false ) {
        $field = $newFieldValue;
    }
}

/**
 * string getLinkAbsolutePath(string $requestFromPageURL, string $targetHref)
 *
 * Returns an absolute-path URL, converting the given relative path to a full path.
 * This is meant to be used to resolve the targets of anchors, weather they be on BusinessUSA, or on another domain.
 */
function absolute_links_imgs_in_markup_getLinkAbsolutePath($requestFromPageURL, $targetHref) {
    
    if ( strval($targetHref) === '' ) {
        return '';
    }
    
    $urlInfo = parse_url($requestFromPageURL);
    $pathInfo = pathinfo($urlInfo['path']);
    
    if ( substr($targetHref, 0, 1) === '/' ) {
        return 'http://' . $urlInfo['host'] . $targetHref;
    }
    
    if ( substr($targetHref, 0, 2) === './' ) {
        $targDir = $pathInfo['dirname'];
        if ( substr($requestFromPageURL, -1) === '/' ) {
           $targDir = str_replace( '//', '/', $targDir . '/' . $pathInfo['filename'] );
        }
        return 'http://' . $urlInfo['host'] . $targDir . substr($targetHref, 1);
    }
    
    if ( substr($targetHref, 0, 3) === '../' ) {
        $targDir = explode('/', $pathInfo['dirname']);
        array_pop($targDir);
        $targDir = implode('/', $targDir);
        return 'http://' . $urlInfo['host'] . $targDir . substr($targetHref, 2);
    }
    
    if ( substr($targetHref, 0, 7) === 'http://' ) {
        return $targetHref;
    }
    
    if ( substr($targetHref, 0, 8) === 'https://' ) {
        return $targetHref;
    }
    
    if ( substr($targetHref, 0, 7) === 'mailto:' ) {
        return $targetHref;
    }
    
    return rtrim($requestFromPageURL, '/') . '/' . $targetHref;
}