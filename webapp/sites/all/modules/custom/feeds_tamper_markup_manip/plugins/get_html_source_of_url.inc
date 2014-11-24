<?php

/**
 * @file
 * Given HTML for this field, alter all anchors/images 
 */

$plugin = array(
  'form' => 'feeds_tamper_get_html_source_of_url_form',
  'callback' => 'feeds_tamper_get_html_source_of_url_callback',
  'name' => 'Get HTML source of target URL',
  'multi' => 'loop',
  'category' => 'Markup Tools and Manipulation',
);

function feeds_tamper_get_html_source_of_url_form($importer, $element_key, $settings) {

    $form = array();
    
    $form['welcomemsg'] = array(
        '#type' => 'markup',
        '#markup' => '
            <br/>
            This Feed Tamper will pull HTML from a target URL, and place the retrieved HTML in this field. <br/>
            The HTML can thus be saved into this field in Drupal, or be further manipulated by another Feed Tamper<br/>
            <br/>
        '
    );
    
    $form['urlsourcemode'] = array(
        '#type' => 'radios',
        '#title' => 'The URL for which HTML should be pulled from... ',
        '#default_value' => 'field',
        '#options' => array(
            'field' => 'Is directly in this field (either supplied in this field by the Feeds module, or by a previously run Feed Tamper)',
            'xpath' => 'Can be found with an XPath query [supplied below]',
            'static' => 'Is always a static URL [that will be supplied below]'
        )
    );
    if ( !empty($settings['urlsourcemode']) ) {
        $form['urlsourcemode']['#default_value'] = $settings['urlsourcemode'];
    }
    
    $form['xpathquery'] = array(
        '#type' => 'textfield',
        '#title' => 'XPath query',
        '#default_value' => '//a[1]/@href',
        '#description' => 'The given XPath query will be run against the input for this Feed Tamper, the result of this XPath query will be used as the URL to pull data/HTML from. <br/>Please note that if your XPath query returned more than on result (an array), only the first element will be used.',
        '#states' => array(
            'visible' => array(
                'input[name="settings[urlsourcemode]"]' => array( 'value' => 'xpath' )
            )
        )
    );
    if ( !empty($settings['xpathquery']) ) {
        $form['xpathquery']['#default_value'] = $settings['xpathquery'];
    }
    
    $form['staticpath'] = array(
        '#type' => 'textfield',
        '#title' => 'Static URL to pull HTML from',
        '#default_value' => 'http://dalefrey.net/',
        '#states' => array(
            'visible' => array(
                'input[name="settings[urlsourcemode]"]' => array( 'value' => 'static' )
            )
        )
    );
    if ( !empty($settings['staticpath']) ) {
        $form['staticpath']['#default_value'] = $settings['staticpath'];
    }
    
    $form['forceabsolute'] = array(
        '#title' => "Transform the target URL to an absolute-path, if it is a relative link",
        '#type' => 'checkbox',
        '#description' => 'Requieres the curl_get_contents(string $url) function to be defined.',
        '#default_value' => 0
    );
    if ( !empty($settings['forceabsolute']) ) {
        $form['forceabsolute']['#default_value'] = $settings['forceabsolute'];
    }
    
    $form['absolutedomain'] = array(
        '#type' => 'textfield',
        '#title' => 'When transforming the URL from a relative-path to an absolute path, assume it comes from the domain of ',
        '#default_value' => 'http://dalefrey.net/',
        '#states' => array(
            'visible' => array(
                'input[name="settings[forceabsolute]"]' => array( 'checked' => true )
            )
        )
    );
    if ( !empty($settings['absolutedomain']) ) {
        $form['absolutedomain']['#default_value'] = $settings['absolutedomain'];
    }
    
    $form['usecurl'] = array(
        '#title' => "Use the <a href=\"http://us2.php.net/manual/en/book.curl.php\">CURL lib</a> when <a href=\"http://us3.php.net/manual/en/function.file-get-contents.php\">file_get_contents()</a> fails",
        '#type' => 'checkbox',
        '#default_value' => 1
    );
    if ( !empty($settings['usecurl']) ) {
        $form['usecurl']['#default_value'] = $settings['usecurl'];
    }
    
    $form['debugging'] = array(
        '#title' => "Send debugging messages with verbosity to php's error log",
        '#type' => 'checkbox',
        '#description' => 'With this checkbox ticked, error_log() will be called to report what URL(s) are being used pull to HTML from.',
        '#default_value' => 0
    );
    if ( !empty($settings['debugging']) ) {
        $form['debugging']['#default_value'] = $settings['debugging'];
    }
    
    return $form;
}



function feeds_tamper_get_html_source_of_url_callback($result, $item_key, $element_key, &$field, $settings, $source) {

    // Debugging and verbosity
    $writeDebugMsgs = intval($settings['debugging']);
    if ( $writeDebugMsgs ) {
        error_log("feeds_tamper_get_html_source_of_url_callback has been invoked with the following settings: " . print_r($settings, true));
        //error_log("and it will tamper with the following value: " . $field);
    }
    
    // Determin the URL
    $url = false;
    switch ( $settings['urlsourcemode'] ) {
        case 'field':
            $url = trim($field);
            break;
        case 'xpath':
            $doc = new DOMDocument();
            @$doc->loadHTML($field);
            $xpath = new DomXPath($doc);
            $xpathQueryToFindTags = trim($settings['xpathquery']);
            $foundTags = $xpath->query($xpathQueryToFindTags);
            if ( intval($foundTags->length) === 0 ) {
                if ( $writeDebugMsgs ) { error_log("WARNING: No tags found for the XPath query: " . $xpathQueryToFindTags); }
            } else {
                foreach ( $foundTags as $foundTag ) {
                    $url = $foundTag->nodeValue;
                    break;
                }
            }
            break;
        case 'static':
            $url = trim($settings['staticpath']);
            break;
    }
    
    // Validation - ensure we have a UTL to oull HTML from 
    if ( $url === false || trim($url) == '' ) {
        if ( $writeDebugMsgs ) { error_log("feeds_tamper_get_html_source_of_url_callback error; A URL could not be found/determined."); }
        return;
    }
    
    // Transform the given URL to an absolute path if requested 
    if ( intval($settings['forceabsolute']) === 1 ) {
        $oldUrl = $url;
        $url = feeds_tamper_get_html_source_of_url_getLinkAbsolutePath($settings['absolutedomain'], $url);
        if ( $writeDebugMsgs ) {
            error_log("feeds_tamper_get_html_source_of_url_callback has transformed the URL from {$oldUrl} to {$url}");
        }
    } else {
        // Debug print the URL
        if ( $writeDebugMsgs ) {
            error_log("feeds_tamper_get_html_source_of_url_callback determined the URL to pull HTML from shall be: {$url}");
        }
    }
    
    $html = false;
    $html = @file_get_contents($url);
    if ( $html === false ) {
        if ( intval($settings['usecurl']) === 1 ) {
            if ( $writeDebugMsgs ) { error_log("feeds_tamper_get_html_source_of_url_callback error; file_get_contents('{$url}') returned false, now trying to use the cURL lib..."); }
            $html = curl_get_contents($url);
            if ( $writeDebugMsgs ) {
                if ( $html === false ) {
                    error_log("feeds_tamper_get_html_source_of_url_callback has failed to obtain content from {$url}");
                } else {
                    error_log("feeds_tamper_get_html_source_of_url_callback was abel to get the content of {$url} by the usage of curl_get_contents()");
                }
            }
        } else {
            error_log("feeds_tamper_get_html_source_of_url_callback has failed to obtain content from {$url} and is not configured to try using the cURL library");
        }
    }
    
    $field = strval($html);
}


/**
 * string getLinkAbsolutePath(string $requestFromPageURL, string $targetHref)
 *
 * Returns an absolute-path URL, converting the given relative path to a full path.
 * This is meant to be used to resolve the targets of anchors, weather they be on BusinessUSA, or on another domain.
 */
function feeds_tamper_get_html_source_of_url_getLinkAbsolutePath($requestFromPageURL, $targetHref) {
    
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