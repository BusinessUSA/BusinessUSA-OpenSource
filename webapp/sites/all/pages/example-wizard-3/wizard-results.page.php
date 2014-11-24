<?php

kprint_r($_REQUEST);

// Create an renderable array for wizard results, to send into: theme('yawizard_sections' <RenderableArray>)
$wizardResultsRenderableArray = array(
    'sections' => array(),
    'titles' => array(),
    'legend' => array()
);

// Define two sections to show, we will reference them as "pets" and "techies" - these are only machine name, not human readable labels
// They will be used as CSS classes in the rendered output, but not visible to humans.
$wizardResultsRenderableArray['sections'] = array(
    'pets' => array(),
    'techies' => array()
);

// Here we will give the 2 sections human-readable titles/labels
$wizardResultsRenderableArray['titles'] = array(
    'pets' => 'As a pet owner, you may be interested in',
    'techies' => 'Tech information you may be interested in',
);

// We will have the yawizard_sections.tpl.php template render a legend (this is optional). This will show at the top of the results slide.
$wizardResultsRenderableArray['legend'] = array(
    'pets' => array(
        'title' => 'Pet Info',
        'img' => 'sites/all/themes/bizusa/images/content-type-icons-small/program.png'
    ),
    'techies' => array(
        'title' => 'Tech Info',
        'img' => 'sites/all/themes/bizusa/images/content-type-icons-small/tools.png'
    )
);

// Now we will populate results into the "pets" section of thw wizard results
if ( intval($_REQUEST['inputs']['has_pets']) === 1 ) {

    if ( intval($_REQUEST['inputs']['pet_dog']) === 1 ) {
        for ( $x = 0 ; $x < 3 ; $x++ ) {
            $wizardResultsRenderableArray['sections']['pets'][] = array(
                'title' => 'Something a dog owner may be interested in ' . ($x+1),
                'link' => 'http://google.com/',
                'snippet' => 'This is a snippet implemented in line ' . __LINE__,
                'tags' => 'dog tag1 tag2',
                'tag_count' => 3,
                'all_tags' => array(),
                'type' => 'pets',
                'promoted' => 0
            );
        }
    }
    
    if ( intval($_REQUEST['inputs']['pet_cat']) === 1 ) {
        for ( $x = 0 ; $x < 4 ; $x++ ) {
            $wizardResultsRenderableArray['sections']['pets'][] = array(
                'title' => 'Something a cat owner may be interested in ' . ($x+1),
                'link' => 'http://google.com/',
                'snippet' => 'This is a snippet implemented in line ' . __LINE__,
                'tags' => 'cat tag1 tag2',
                'tag_count' => 3,
                'all_tags' => array(),
                'type' => 'pets',
                'promoted' => 0
            );
        }
    }
    
}

// Now we will populate results into the "techies" section of thw wizard results
if ( intval($_REQUEST['inputs']['tech_guru']) === 1 ) {

    if ( intval($_REQUEST['inputs']['tech_security']) === 1 ) {
        for ( $x = 0 ; $x < 3 ; $x++ ) {
            $wizardResultsRenderableArray['sections']['techies'][] = array(
                'title' => 'Something a security-person may be interested in ' . ($x+1),
                'link' => 'http://google.com',
                'snippet' => 'This is a snippet',
                'tags' => 'tag1 tag2',
                'tag_count' => 2,
                'all_tags' => array(),
                'type' => 'techies',
                'promoted' => 0
            );
        }
    }
    
    if ( intval($_REQUEST['inputs']['tech_php']) === 1 ) {
        for ( $x = 0 ; $x < 4 ; $x++ ) {
            $wizardResultsRenderableArray['sections']['techies'][] = array(
                'title' => 'Something a PHP-developer owner may be interested in ' . ($x+1),
                'link' => 'http://google.com',
                'snippet' => 'This is a snippet',
                'tags' => 'tag1 tag2',
                'tag_count' => 2,
                'all_tags' => array(),
                'type' => 'techies',
                'promoted' => 0
            );
        }
    }
    
}

// Render results HTML through yawizard_sections.tpl.php
print theme('yawizard_sections', $wizardResultsRenderableArray);