jQuery(document).ready( function () {
    
    // Dont scan Drupal administration pages
    if ( String(document.location.pathname).indexOf('admin/') != -1 ) {
        return;
    }
    
    if ( String(document.location.pathname).indexOf('_') != -1 ) {
    
        var msg = 'Underscore(s) in the URL to this page was detected. ';
        msg += 'Underscores should not exist in the URL to any content page of the site for SEO purposes. ';
        msg += 'Please replace underscores with dashes for the alias path to this node/page.';
        jquery_codescanner.reportIssueToServer('Underscore in URL', msg);
    }
    
});