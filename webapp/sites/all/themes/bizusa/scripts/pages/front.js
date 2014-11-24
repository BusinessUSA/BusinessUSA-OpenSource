function detectIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');
    var trident = ua.indexOf('Trident/');

    if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }

    if (trident > 0) {
        // IE 11 (or newer) => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }

    // other browser
    return 1000;
}
if ( detectIE() <= 8 ) {
    window.location="/notsupported_browser";
}

(function ($) {
	$(document).ready(function(){

        /* Front Page - Wizard list */
        $('#carousel1 .slider').bxSlider({
            controls: true,
            auto: true,
            adaptiveHeight: false,
            autoStart: false
        });

		/* Front Page - Featured Item, Business News, Whats New, Quick Facts */
        $('#carousel2 .slider').bxSlider({
            controls: false,
            auto: true,
            adaptiveHeight: false,
            autoStart: true
        });

        // View or Hide Map in Locate Closest Resources & Events
        $("#btn-map").click(function() {
            
            if ( $("#btn-map span").hasClass("glyphicon-map-marker") ) {
                $("#btn-map span").removeClass("glyphicon-map-marker").addClass("glyphicon-th-list");
                //locateResourcesAndEvents.goToNextSlide();
                //locateResourcesAndEvents.goToPrevSlide();
                //jQuery('.resevents-googlemap-container').slideDown();
                jQuery('#locationsList').hide();
                //jQuery('.resevents-googlemap-container').slideDown();

                $('.resevents-googlemap-container').slideDown('slow', function() {
                    resizeBottomRow();
                });
                
            } else {
                $("#btn-map span").removeClass("glyphicon-th-list").addClass("glyphicon-map-marker");
                //locateResourcesAndEvents.goToPrevSlide();
                //locateResourcesAndEvents.goToNextSlide();
                jQuery('#locationsList').show();

                jQuery('.resevents-googlemap-container').slideUp('slow', function(){
                    resizeBottomRow();
                });
                
                //jQuery('div[id="locationsList"]').slideUp();
            }



        });

        // Adjust column heights
        resizeTopRow();
        resizeBottomRow();

        if ($('.center-block').attr('src').length > 0)
        {
            if ($('.center-block').attr('src').indexOf('Disaster') > 0)
            {
                $('.center-block').attr('alt', 'Disaster Assistance Logo');
            }
        }


	});
})(jQuery);

function resizeTopRow () {

    //Reset Column Heights  
    $( "#wizard-list-with-description .list-group .list-group-item" ).each(function() { $(this).removeAttr("style"); });
    $("#wizard-list-all .bx-pager").removeAttr("style");
    $("#news-section-carousel #carousel2").removeAttr("style");

    var columnOneHeight = $("#wizard-list-with-description").height();
    var columnTwoHeight = $("#wizard-list-all").height();
    var columnThreeHeight = $("#news-section-carousel").height();

    var tallestColumn = Math.max(columnOneHeight, columnTwoHeight, columnThreeHeight);

    if(columnThreeHeight == tallestColumn){

        // Adjust Column One height
        resizeTopRowColumnOne(columnThreeHeight);

        // Adjust Column Two height
        //var columnTwoOffset = parseInt($("#wizard-list-all .bx-pager").css("padding-top")) + (columnThreeHeight - columnTwoHeight );
        //$("#wizard-list-all .bx-pager").css("padding-top", columnTwoOffset + "px");

        var columnTwoOffset = columnThreeHeight - columnTwoHeight;
        $("#wizard-list-all .bx-pager").css("margin-top", columnTwoOffset + "px");
        

    }else if(columnTwoHeight == tallestColumn){

        // Adjust Column One height
        resizeTopRowColumnOne(columnTwoHeight);

        // Adjust Column Three height
        var columnThreeOffset = columnTwoHeight - columnThreeHeight;
        var columnTwoNewHeight = parseInt($("#news-section-carousel .bx-viewport").css("height")) + columnThreeOffset;
        $("#news-section-carousel #carousel2").css("height", columnTwoNewHeight );
        $("#news-section-carousel #carousel2").css("background-color", "#fff" );
    }
}


// Column One Top Row - Adjust Height
function resizeTopRowColumnOne(selectedTallestColumn){

    var columnOneOffset = selectedTallestColumn - $("#wizard-list-with-description").height();
    var columnOneItems = parseInt($( "#wizard-list-with-description .list-group .list-group-item" ).length);

    var columnOneItemsPadding = (columnOneOffset / columnOneItems) / columnOneItems;

    $( "#wizard-list-with-description .list-group .list-group-item" ).each(function() {            
        var columnOneTopPadding = parseInt($(this).css("padding-top")) + columnOneItemsPadding;
        var columnOneBottomPadding = parseInt($(this).css("padding-bottom")) + columnOneItemsPadding;
        
        // Add Padding
        $(this).css("padding-top", columnOneTopPadding + "px");
        $(this).css("padding-bottom", columnOneBottomPadding + "px");
        
        // Move Icons
        var backgroundPositions = $(this).css("background-position").split(" ");
        var newYPos = parseInt(backgroundPositions[1]) + columnOneItemsPadding ;
        var newBackgroundPosition = backgroundPositions[0] + " " + newYPos + "px"
        $(this).css("background-position", newBackgroundPosition );
    });
}


function resizeBottomRow () {
    $(".panel.panel-success-stories").css("height", $("#frontpage-locateresources .panel.panel-primary").height() + "px" );
}


$(window).resize(function () {
    resizeTopRow();
    resizeBottomRow();
});