/**
 * Created by sanjay.gupta on 7/31/14.
 */




(function ($) {
    $(document).ready(function(){
        var sidebarHeader = $('div#boxes-box-search_page_refine_by_title').find('div.boxes-box-content');

        if (sidebarHeader != undefined){
            $(sidebarHeader).attr({
                "data-toggle" : "collapse",
                "data-parent" : "#accordion1",
                "href" : "#collapseOne"
            });
        }

    $('.block-facetapi').wrap( "<div id='collapseOne' class='panel-collapse collapse in'></div>");

    // Add panel collapse arrow

    $('.search-page-sidebartitle-refineby').css( "background", "url('/sites/all/themes/bizusa/images/icons/block-expanded.png') no-repeat 92% center #e2e2e2");
    $('.boxes-box-content.collapsed h1').css( "background", "url('/sites/all/themes/bizusa/images/icons/block-collapsed.png') no-repeat 92% center #e2e2e2");

    $('.panel-collapse').on('hidden.bs.collapse', function (e) {
        var panelID = String($(e.target).attr('id'));
        $('.boxes-box-content.collapsed h1').css( "background", "url('/sites/all/themes/bizusa/images/icons/block-collapsed.png') no-repeat 92% center #e2e2e2");
    });

    $('.panel-collapse').on('shown.bs.collapse', function (e) {
        var panelID = String($(e.target).attr('id'));
        $('.search-page-sidebartitle-refineby').css( "background", "url('/sites/all/themes/bizusa/images/icons/block-expanded.png') no-repeat 92% center #e2e2e2");
    });

    });
})(jQuery);
