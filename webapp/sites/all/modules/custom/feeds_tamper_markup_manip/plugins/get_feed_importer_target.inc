<?php

$plugin = array(
  'form' => 'feeds_tamper_get_feed_importer_target_form',
  'callback' => 'feeds_tamper_get_feed_importer_target_callback',
  'name' => 'Set to the FeedImporter-Fetcher\'s Target-URL',
  'multi' => 'loop',
  'category' => 'Other',
);

function feeds_tamper_get_feed_importer_target_form($importer, $element_key, $settings) {

    $form = array();
    
    $form['welcomemsg'] = array(
        '#type' => 'markup',
        '#markup' => 'WARNING: This has been tested and verified to work with the "HTTP Fetcher", any other fetchers is not guaranteed to work with this Tamper.'
    );
    $form['spacer'] = array(
        '#type' => 'markup',
        '#markup' => '<br/>'
    );
    
    $form['injectmode'] = array(
        '#type' => 'radios',
        '#title' => 'The Feed Importer\'s target URL shall',
        '#default_value' => 'replace',
        '#options' => array(
            'replace' => 'replace the value of the target [Tamper] field. This will destory the previous value in the [Tamper] field, the URL will be the only thing in this filed after this Tamper has executed.',
            'prepend' => 'be prepended to the beginning of the [string] value in the target [Tamper] field.',
            'append' => 'be appended to the end of the [string] value in the target [Tamper] field.'
        )
    );
    if ( !empty($settings['injectmode']) ) {
        $form['injectmode']['#default_value'] = $settings['injectmode'];
    }
    
    $form['rewriteurl'] = array(
        '#type' => 'textfield',
        '#title' => 'Rewrite URL as',
        '#default_value' => '[url]',
        '#description' => 'Leave as "[url]" if you do not want to rewrite the URL. You can use this field to append/prepend string(s) around the URL. Use [url] as a replacement pattern for the URL the Feed Importer is fetching data from.'
    );
    if ( !empty($settings['rewriteurl']) ) {
        $form['rewriteurl']['#default_value'] = $settings['rewriteurl'];
    }
    
    return $form;
}

function feeds_tamper_get_feed_importer_target_callback($result, $item_key, $element_key, &$field, $settings, $source) {
    
    if ( !isset($result->link) || !is_string($result->link) ) {
        error_log('Error: feeds_tamper_get_feed_importer_target_callback() could not determin the target URL the Feed Importer is fetching data from.');
        return;
    }
    
    if ( isset($settings['rewriteurl']) ) {
        $rewriteUrlAs = $settings['rewriteurl'];
    } else {
        $settings['rewriteurl'] = '[url]';
    }
    $fetcherUrl = str_replace('[url]', $result->link, $rewriteUrlAs);
    
    switch ( $settings['injectmode'] ) {
        case 'prepend':
            $field = $fetcherUrl . $field;
            break;
            
        case 'append':
            $field .= $fetcherUrl;
            break;
            
        case 'replace':
        default:
        
            $field = $fetcherUrl;
            break;
    }

    
}









