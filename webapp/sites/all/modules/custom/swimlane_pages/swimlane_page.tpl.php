
<div class="legacyswimlane-mastercontainer" rendersource="<?php print __FILE__; ?>">
    
    <script>
        jQuery('body').addClass('page-resource');
        jQuery('body').addClass('not-edit-mode');
    </script>
    
    <div class="legacyswimlane-bodycontainer">

        <div class="subnav-container">
            <ul>
                <li class="subnav-header" style="display:fixed;">
                    Related Wizards
                </li>
                <li class="subnav-header swimlane-edit-only" style="display:fixed;"><a href="/admin/swimlanepages/edit/footer?pageuri=<?php print request_uri(); ?>">Edit Nav Menu</a></li>
                <?php $count = 0; ?>
                <?php foreach( $variables["subnav_header_links"] as $link_arr ): ?>
                    <li class="<?php print ( $count % 2 == 1 ? 'odd' : 'even' ); ?>">
                        <a href="<?php echo $link_arr['link'];?>" title="<?php echo $link_arr['text'];?>">
                            <?php echo $link_arr['text'];?>
                        </a>
                    </li>
                    <?php $count++; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="editting-options">
            <span class="swimlane-edit-only">
                [<a href="/admin/swimlanepages/add/section?pageuri=<?php print request_uri(); ?>">Add a new Section</a>]
            </span>
            <span class="swimlane-edit-only">
                [<a href="/admin/swimlanepages/edit/section-order?pageuri=<?php print request_uri(); ?>">Change order of Sections</a>]
            </span>
            <span class="swimlane-edit-never auth-only">
                [<a href="javascript: jQuery('body').toggleClass('not-edit-mode').toggleClass('edit-mode'); void(0);">Enter Edit Mode</a>]
            </span>
            <span class="swimlane-edit-only auth-only">
                [<a href="javascript: jQuery('body').toggleClass('not-edit-mode').toggleClass('edit-mode'); void(0);">Leave Edit Mode</a>]
            </span>
        </div>
        
        <?php foreach ( $variables['sections'] as $sectionId => $section ): ?>
            <div class="legacyswimlane-section">
                <span class="swimlane-edit-only swimlane-edit-title">
                    <span>
                        [<a href="/admin/swimlanepages/edit/section?pageuri=<?php print request_uri(); ?>&sectionId=<?php print $sectionId; ?>">Change Section Properties</a>]
                    </span>
                    <span>
                        [<a href="/admin/swimlanepages/edit/section-block-order?pageuri=<?php print request_uri(); ?>&sectionId=<?php print $sectionId; ?>">Change order of Section-Blocks</a>]
                    </span>
                    <span>
                        [<a href="/admin/swimlanepages/add/section-block?pageuri=<?php print request_uri(); ?>&sectionId=<?php print $sectionId; ?>">Add a new Section-Block</a>]
                    </span>
                </span>
                <h2>
                    <?php print $section['title']; ?>
                </h2>
                <ul class="legacyswimlane-blocks-container">
                <?php $counter = 0; ?>
                <?php foreach ( $section['blocks'] as $blockId => $block ): ?>
                    <?php $oddEven = ($counter % 2) ? 'even' : 'odd'; ?>
                    <li class="legacyswimlane-block <?php echo $oddEven; ?>">
                        <div class="page-featured-top">
                            <h3 class="legacyswimlane-block-linkedtitle">
                                <a class="" href="<?php print $block['url']; ?>">
                                    <?php print $block['title']; ?>
                                </a>
                            </h3>
                            <br/>
                            <span class="legacyswimlane-block-snippet">
                                <?php print $block['snippet']; ?>
                            </span>
                        </div>
                        <span class="swimlane-edit-only swimlane-edit-block">
                            [<a href="/admin/swimlanepages/edit/section-block?pageuri=<?php print request_uri(); ?>&sectionId=<?php print $sectionId; ?>&blockId=<?php print $blockId; ?>">
                                Edit this Section-Block
                            </a>]
                        </span>
                    </li>
                    <?php $counter++; ?>
                <?php endforeach; ?>
                </ul>
                <?php if($section['url']): ?>
                <div class="swimlane-morelink-container">
    				<a href="<?php print $section['url']; ?>" class="swimlane-morelink-link ">
    					See More
    				</a>
    			</div>
                <?php endif;?>
            </div>
        <?php endforeach; ?>
    
    </div>

    <div class="legacyswimlane-foot-container">
        <?php if ( !empty($variables['foot']) ): ?>
            <h2>
                <?php print $variables['foot']['title']; ?> 
                <span class="swimlane-edit-only swimlane-edit-footer">
                    [<a href="/admin/swimlanepages/edit/footer?pageuri=<?php print request_uri(); ?>">Change footer title</a>]
                    [<a href="/admin/swimlanepages/add/footerblock?pageuri=<?php print request_uri(); ?>">Add a Footer-Block</a>]
                </span>
            </h2>
            <ul class="legacyswimlane-foot-blocks-container blocks-count-<?php print count($variables['foot']['blocks']); ?>">
                <?php foreach ( $variables['foot']['blocks'] as $blockId => $block ): ?>
                    <li class="legacyswimlane-foot-block">
                        <div class="imgcontainer">
                            <img src="<?php print $block['img']; ?>" onerror="jQuery(this).hide().attr('note', 'Hidden by jQuery at Coder Bookmark: CB-60B8N6M-BC');" />
                        </div>
                        <a href="<?php print $block['url']; ?>">
                            <?php print $block['title']; ?>
                        </a>
                        <div class="swimlane-edit-only swimlane-edit-footer">
                            <a href="/admin/swimlanepages/edit/footerblock?pageuri=<?php print request_uri(); ?>&blockId=<?php print $blockId; ?>">Edit</a>
                        </div>
                   </li> 
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>