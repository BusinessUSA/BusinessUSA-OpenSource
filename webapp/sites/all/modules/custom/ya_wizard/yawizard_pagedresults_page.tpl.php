<?php $uniqVar = $variables['uniqVar']; ?>

<div class="yawizard-pagedresults-page yawizard-pagedresults-page-<?php print $variables['pageId']; ?> yawizard-pagedresults-pagecount-<?php print $variables['pageCount']; ?>" rendersource="<?php print basename(__FILE__); ?>" <?php print $variables['containerAttribs']; ?>>
    <div class="yawizard-pagedresults-results-container">
        <?php foreach ( $variables['resultMarkups'] as $resultMarkup ): ?>
            <div class="yawizard-pagedresults-result">
                <?php print $resultMarkup; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <ul class="pager">
        <?php if ( $variables['pageId'] > 1 ): ?>
            <li class="pager-first first">
                <a href="<?php print "javascript: jQuery('#{$uniqVar}').html({$uniqVar}[0]); {$uniqVar}_counter = 0; {$uniqVar}_hook_pagechange(); void(0);"; ?>">
                    &lt;&lt;
                </a>
            </li>
            <li class="pager-pageto-prev">
                <a href="<?php print "javascript: {$uniqVar}_counter--; jQuery('#{$uniqVar}').html({$uniqVar}[{$uniqVar}_counter]); {$uniqVar}_hook_pagechange(); void(0);"; ?>">
                    &lt; Prev
                </a>
            </li>
            <li class="ellipses ellipses-left">
                ...
            </li>
        <?php endif; ?>
        <?php for ( $pager = $variables['pagerLinksStart'] ; $pager <= $variables['pagerLinksEnd'] ; $pager++  ): ?>
            <li class="pager-pageto-<?php print $pager ?> <?php if ( $variables['pageId'] == $pager ) { print ' pager-current'; } ?><?php if ( $variables['pageId'] == $variables['pageCount'] ) { print ' pager-last-page'; } ?><?php if ( $pager == 1 ) { print ' pager-first-page'; } ?>">
                <?php $prevPager = $pager - 1; ?>
                <a href="<?php print "javascript: jQuery('#{$uniqVar}').html({$uniqVar}[{$prevPager}]); {$uniqVar}_counter = {$prevPager}; {$uniqVar}_hook_pagechange(); void(0);"; ?>">
                    <?php print $pager ?>
                </a>
            </li>
        <?php endfor; ?>
        <?php if ( $variables['pageId'] < $variables['pageCount'] ): ?>
            <li class="ellipses ellipses-right">
                ...
            </li>
            <li class="pager-pageto-next">
                <a href="<?php print "javascript: {$uniqVar}_counter++; jQuery('#{$uniqVar}').html({$uniqVar}[{$uniqVar}_counter]); {$uniqVar}_hook_pagechange(); void(0);"; ?>">
                    Next &rsaquo;
                </a>
            </li>
            <li class="pager-last last">
                <a href="<?php print "javascript: jQuery('#{$uniqVar}').html({$uniqVar}[{$variables['pageCount']}]); {$uniqVar}_counter = {$pageCount}; {$uniqVar}_hook_pagechange(); void(0);"; ?>">
                    Last &raquo;
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>