/*
    Feel free to override these hooks, however, remember to trigger the wizContinueCallback() function when done with the hook.
    If you fail to do so, aspects of the wizard may remain frozen.
*/
jQuery.extend(yawizard, {
    
    hook_slide_transition: function (slideFrom, slideTo, wizContinueCallback) {
    
        if ( jQuery(window).width() < 768 ) {
        
            if ( slideFrom.hasClass('slide-current') && slideTo.hasClass('slide-current') ) {
                if ( slideFrom.hasClass('collapsed') ) {
                    slideFrom.slideDown( function () {
                        slideFrom.removeClass('collapsed');
                        slideFrom.addClass('uncollapsed');
                        wizContinueCallback(false);
                    });
                } else {
                    slideTo.slideUp( function () {
                        slideFrom.removeClass('uncollapsed');
                        slideFrom.addClass('collapsed');
                        wizContinueCallback(false);
                    });
                }
            } else {
                slideFrom.slideUp( function () {
                    slideFrom.removeClass('uncollapsed');
                    slideFrom.addClass('collapsed');
                    yawizard.validateDependencyOnNavigation();
                    slideTo.slideDown( function () {
                        slideFrom.removeClass('collapsed');
                        slideFrom.addClass('uncollapsed');
                        wizContinueCallback();
                    });
                });
            }
        } else {
            
            if ( slideFrom.attr('class') == slideTo.attr('class') ) {
                //alert('You are already on this slide /* Coder Bookmark: CB-SR7CE8B-BC */');
            } else {
                slideFrom.fadeOut( function () {
                    yawizard.validateDependencyOnNavigation();
                    slideTo.fadeIn( function () {
                        wizContinueCallback();
                    });
                });
            }
        }
        
    },
    
    hook_slideseeker_show_transition: function (slideSeeker, wizContinueCallback) {
        slideSeeker.show();
        wizContinueCallback();
    },
    
    hook_slideseeker_hide_transition: function (slideSeeker, wizContinueCallback) {
        slideSeeker.hide();
        wizContinueCallback();
    },
    
    hook_question_show_transition: function (questionElementContainer, wizContinueCallback) {
        questionElementContainer.show();
        questionElementContainer.css('opacity', '');
        questionElementContainer.fadeIn( function () {
            wizContinueCallback();
        });
    },
    
    hook_question_hide_transition: function (questionElementContainer, wizContinueCallback) {
        questionElementContainer.hide();
        questionElementContainer.fadeOut( function () {
            wizContinueCallback();
        });
    },
    
});

