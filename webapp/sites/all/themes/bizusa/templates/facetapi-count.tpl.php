<?php /*
    DEV NOTE: This template file overrides theme_facetapi_count, which is defined in facetapi.theme.inc
    This template renders the facet-result-count area within the facet blocks on the search results page.
*/ ?>
<span class="facet-result-count" rendersource="facetapi-count.tpl.php">
    <?php print $variables['count']; ?>
</span>