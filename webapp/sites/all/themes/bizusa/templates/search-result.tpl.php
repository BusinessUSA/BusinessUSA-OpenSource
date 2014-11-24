<?php

    // Expose node (loaded from database) into $variables
    if ( !empty($variables['result']['node']->entity_id) || strpos(request_uri(), '-DEBUG-STOP-FULLNODE-LOAD-') === false ) {
        $variables['fullNode'] = node_load($variables['result']['node']->entity_id);
    }

    // Search result container-divs shall have the search-result title as one of its CSS classes
    $titleInContainerClass = $title;
    if ( function_exists('cssFriendlyString') ) {
        $titleInContainerClass = cssFriendlyString($titleInContainerClass); // Function defined in Miscellaneous-SharedFunctions.php
    }
?>
<div class="search-result-container <?php print $classes; ?> search-result-<?php print $variables['result']['bundle']; ?> search-result-title-<?php print $titleInContainerClass ?>">
    <?php print render($title_prefix); ?>
    <div class="search-result-content-type">
      <?php 
      
        /* Determin the URL for the icon for this search-result */
        $iconURL = base_path() . path_to_theme() . '/images/content-types-icons/34x34/' . $variables['result']['bundle'] . '.png';
        // Check if there is an override for this result-icon (if this node has data in the search_icon_override field)
        if ( !empty($variables['fullNode']->field_search_icon_override['und'][0]['url']) ) {
            $iconURL = $variables['fullNode']->field_search_icon_override['und'][0]['url'];
        }

      ?>
      <img src="<?php print $iconURL; ?>" class="search-result-icon-img" alt="Icon for this <?php echo $variables['result']['bundle']; ?> result" note="This img is rendered from the <?php print basename(__FILE__); ?> file">

      <!-- Label of this CONTENT-TYPE (shown under the search-result icon) -->
      <?php
            /* Determin the title of this content-type (to be shown directly below the icon for the search result) */
            $cTypeTitle = $variables['result']['type'];
            // Based on the content-type, we may apply certain overrides here (i.e. we shall say "Wizard" instead of "Swim-Lane-Page" to the user)
            switch ( strtolower($cTypeTitle) ) {
                case 'wizard-result reference for solr':
                    $cTypeTitle = 'Program';
                    break;
            }
      ?>
      <span class="ptype ptype-is-<?php print $cTypeTitle; ?>">
        <?php print $cTypeTitle; ?>
      </span>
      
    </div>
    
    <div class="search-result-panel-content">
        
        <!-- Title and Link (search result) -->
        <h3 <?php print $title_attributes; ?>>
            <?php 
                
                $forceRelativeLink = true;
                
                // Override link based on content-type if nessesary
                $cTypeCompare = strtolower(rtrim($variables['result']['type'], 's')); 
                print '<!' . '-- cTypeCompare is ' . $cTypeCompare . '--' . '>';
                switch ( $cTypeCompare ) {
                    case 'challenge':
                        if ( !empty($variables['fullNode']->field_challenge_url['und'][0]['url']) ) {
                            print '<!-- This search-result link has been altered since it is of the "challenges" content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-PLSV9M7-BC */ -->';
                            $url = $variables['fullNode']->field_challenge_url['und'][0]['url'];
                            $forceRelativeLink = false;
                        }
                        break;
                    case 'export article':
                        if ( !empty($variables['fullNode']->field_exportrip_origin['und'][0]['value']) ) {
                            print '<!-- This search-result link has been altered since it is of the "export article" content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-OCHLUZL-BC */ -->';
                            $exportOriginUrl = $variables['fullNode']->field_exportrip_origin['und'][0]['value'];
                            $exportOriginUrl = str_replace('export.gov/', '', $exportOriginUrl);
                            $exportOriginUrl = str_replace('www.export.gov/', '', $exportOriginUrl);
                            $url = '/export-portal?' . $exportOriginUrl;
                            $forceRelativeLink = false;
                        }
                        break;
                    case 'grant':
                        if ( !empty($variables['fullNode']->field_grants_link['und'][0]['url']) ) {
                            print '<!-- This search-result link has been altered since it is of the "grants" content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-KG7VKU7-BC */ -->';
                            $url = $variables['fullNode']->field_grants_link['und'][0]['url'];
                            $forceRelativeLink = false;
                        }
                        break;
                    case 'green_sbir_topic':
                        if ( !empty($variables['fullNode']->field_url['und'][0]['url']) ) {
                            print '<!-- This search-result link has been altered since it is of the "green_sbir_topic" content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-00EO11V-BC */ -->';
                            $url = $variables['fullNode']->field_url['und'][0]['url'];
                            $forceRelativeLink = false;
                        }
                        break;
                    case 'knowledgebase article':
                        print '<!-- This search-result link has been altered since it is of the knowledgebase_article content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-7F9IYIG-BC */ -->';
                        $url = $variables['fullNode']->field_para_knowledge_paralink['und'][0]['url'];
                        $forceRelativeLink = false;
                        break;
                    case 'patent':
                        if ( !empty($variables['fullNode']->field_patent_purl_url['und'][0]['url']) ) {
                            print '<!-- This search-result link has been altered since it is of the "patent" content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-0MR1RNG-BC */ -->';
                            $url = $variables['fullNode']->field_patent_purl_url['und'][0]['url'];
                            $forceRelativeLink = false;
                        }
                        break;
                    case 'quick fact':
                        print '<!-- This search-result link has been altered since it is of the Quick Fact content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-NB3T4VU-BC */ -->';
                        $url = '/quick-facts?highlight=' . $variables['result']['node']->entity_id;
                        break;
                    case 'solicitation':
                        if ( !empty($variables['fullNode']->field_presol_link['und'][0]['url']) ) {
                            print '<!-- This search-result link has been altered since it is of the "solicitation" content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-BY88XL0-BC */ -->';
                            $url = $variables['fullNode']->field_presol_link['und'][0]['url'];
                            $forceRelativeLink = false;
                        }
                        break;
                    case 'swim-lane page':
                        if ( !empty($variables['fullNode']->field_swimlane_wizurl['und'][0]['value']) ) {
                            print '<!-- This search-result link has been altered since it is of the "swim-lane page" content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-BAL3WH3-BC */ -->';
                            $url = '/' . $variables['fullNode']->field_swimlane_wizurl['und'][0]['value'];
                        }
                        break;
                    case 'wizard-result reference for solr':
                        if ( !empty($variables['fullNode']->field_wizref_url['und'][0]['value']) ) {
                            print '<!-- This search-result link has been altered since it is of the "wizard-result reference for solr" content-type - this alteration done within ' . basename(__FILE__) . ' /* Coder Bookmark: CB-XG2E8BS-BC */ -->';
                            $url = $variables['fullNode']->field_wizref_url ['und'][0]['value'];
                            $forceRelativeLink = false;
                        }
                        break;
                    default:
                       // No change to the $url variable nessesary
                }

                // Ensure the link is a relative link when nessesary
                if ( $forceRelativeLink ) {
                    $urlParsed = parse_url($url);
                    $url = str_replace('http://', '', $url);
                    $url = str_replace('https://', '', $url);
                    if ( !empty($urlParsed['host']) ) {
                        $url = str_replace($urlParsed['host'], '', $url);
                    }
                    $url = '/' . ltrim($url, '/'); // ensure $url starts with a slash /
                }
                // Redirect the result to '/track-result' to record the no of clicks
                $urlPath = drupal_get_path_alias($_GET['q']);
                $urlPath = str_replace('"', "", $urlPath );
                $pathParams = explode('/', $urlPath);

            ?>
            <a href="<?php print $url; ?>"><?php print $title; ?></a>
        </h3>
        
        <div class="admin-only search-score">
            Score: <?php print $variables['result']['score']; ?>
        </div>
        
        <p class="search-snippet"<?php print $content_attributes; ?>>
			<?php 
            
                /* Determin the content of the snippet */
                $printSnippet = '';
                
                /* Executive Summary for Programs and Services */
				if ( $printSnippet === '' && isset($variables['purpose']) )  {
					$purpose = substr($variables['purpose'], 0, 200);
					$printSnippet = trim( strip_tags($purpose) );
				}
                
                /* Executive Summary for Tools */
                if ( 
                    $printSnippet === ''
                    && isset($variables['fullNode']->field_tools_execsum) 
                    && isset($variables['fullNode']->field_tools_execsum['und'])
                    && isset($variables['fullNode']->field_tools_execsum['und'][0])
                    && !empty($variables['fullNode']->field_tools_execsum['und'][0]['value'])
                ) {
                    $printSnippet = trim( strip_tags($variables['fullNode']->field_tools_execsum['und'][0]['value']) );
                }
                
                /* Snippet for Events */
                if (
                    $printSnippet === ''
                    && isset($variables['fullNode']->field_event_detail_desc)
                    && isset($variables['fullNode']->field_event_detail_desc['und'])
                    && isset($variables['fullNode']->field_event_detail_desc['und'][0])
                    && !empty($variables['fullNode']->field_event_detail_desc['und'][0]['value'])
                ) {
                    //$printSnippet = trim(strip_tags($variables['fullNode']->field_event_detail_desc['und'][0]['value']));
                    print truncate_utf8(trim(strip_tags($variables['fullNode']->field_event_detail_desc['und'][0]['value'])), 253);
                }
                
                /* If this node's body has a summary field */
                if (
                    $printSnippet === ''
                    && isset($variables['fullNode']->body['und'][0]['summary'])
                    && !empty($variables['fullNode']->body['und'][0]['summary'])
                ) {
					$summary = $variables['fullNode']->body['und'][0]['summary'];
					$printSnippet =  trim( strip_tags(substr($summary, 0, 200)) );
				}
                
                /* For content under the "License and Permits" content-type, create a snippet based on information supplied in the node */
                if ( !empty($variables['fullNode']) && $variables['fullNode']->type === 'licenses_and_permits' ) {
                    if ( !empty($variables['fullNode']->field_lap_county['und'][0]['value']) ) {
                        if ( $printSnippet !== '' ) {
                            $printSnippet .= ', ';
                        }
                        $printSnippet .= $variables['fullNode']->field_lap_county['und'][0]['value']; // Add the County to the snippet
                    }
                    if ( !empty($variables['fullNode']->field_lap_city['und'][0]['value']) ) {
                        if ( $printSnippet !== '' ) {
                            $printSnippet .= ', ';
                        }
                        $printSnippet .= $variables['fullNode']->field_lap_city['und'][0]['value']; // Add the City to the snippet
                    }
                    if ( !empty($variables['fullNode']->field_lap_state['und'][0]['value']) ) {
                        if ( $printSnippet !== '' ) {
                            $printSnippet .= ', ';
                        }
                        $printSnippet .= $variables['fullNode']->field_lap_state['und'][0]['value']; // Add the State to the snippet
                    }
                    if ( !empty($variables['fullNode']->field_lap_url['und'][0]['value']) ) {
                        if ( $printSnippet !== '' ) {
                            $printSnippet .= ' - ';
                        }
                        $printSnippet .= $variables['fullNode']->field_lap_url['und'][0]['value']; // Add the associated-link to the snippet
                    }
                    if ( !empty($variables['fullNode']->field_lap_desc['und'][0]['value']) ) {
                        if ( $printSnippet !== '' ) {
                            $printSnippet .= ' - ';
                        }
                        $printSnippet .= $variables['fullNode']->field_lap_desc['und'][0]['value']; // Add the description to the snippet
                    }
                }
                
                /* everything else - use snippet given by Solr (and truncate) */
                if ( $printSnippet === '' ) {
                    $snippet = strip_tags($snippet);
                    if ( strlen($snippet) > 200 ) {
                        $snippet = substr($snippet, 0, 200);
                        $snippet = substr($snippet, 0, strrpos($snippet, ' '));
                        $snippet = trim($snippet) . '...';
                    }
                    $printSnippet = $snippet;
				}
                
                /* But if this result has content in a field_swimlane_wizexcelfile field, consider this an override */
                if ( !empty($variables['fullNode']->field_search_snippet_override['und'][0]['value']) && strlen($variables['fullNode']->field_search_snippet_override['und'][0]['value']) > 3 ) {
                    $printSnippet = $variables['fullNode']->field_search_snippet_override['und'][0]['value'];
                }
                
                print $printSnippet;
			?>
		</p>
    </div>
</div>
