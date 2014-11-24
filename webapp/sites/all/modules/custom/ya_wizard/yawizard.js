yawizard = {
    
    /* will be overridden by wizard/$variable */
    slides: [], 
    
    /* local variables */
    currentActiveSlide: 0,
    maxSlideCount: 0,
    
    /* JavaScript hooks - defining interfaces */
    hook_slide_transition: function (slideFrom, slideTo, wizContinueCallback) {
        wizContinueCallback();
    },
    hook_slideseeker_show_transition: function (slideSeeker, wizContinueCallback) {
        wizContinueCallback();
    },
    hook_slideseeker_hide_transition: function (slideSeeker, wizContinueCallback) {
        wizContinueCallback();
    },
    hook_question_show_transition: function (questionElementContainer, wizContinueCallback) {
        wizContinueCallback();
    },
    hook_question_hide_transition: function (questionElementContainer, wizContinueCallback) {
        wizContinueCallback();
    },
    
    init: function (wizardObj) {
    
        // Identify this page inplementing a YA_Wizard
        jQuery('body').addClass('page-contains-wizard');
        jQuery('body').addClass('page-contains-wizard-' + yawizard.uniqueId);
        
        // We shall start on Slide 0, unless there is an override in the URL
        yawizard.currentActiveSlide = 0;
        if ( document.location.hash.indexOf('wizard-step-id-') != -1 ) {
            var gotoStep = document.location.hash.split('-');
            gotoStep = gotoStep[gotoStep.length - 1];
            yawizard.currentActiveSlide = parseInt(gotoStep);
        }
        
        yawizard.maxSlideCount = jQuery('.slide-selector>li').length;
        
        var myWizard = jQuery('.wizard-uniqueid-' + yawizard['uniqueId']);
        var wizardInputElements = myWizard.find('input,select,a,label');
        wizardInputElements.bind('keydown click', function () {
            yawizard.event_input_element_change();
        });
        
        // If we are in mobile view, enter accordion-mode
        if ( jQuery(window).width() < 768 ) {
            for ( var s = 0 ; s < yawizard.maxSlideCount ; s++ ) {
                var thisSlideSeeker = jQuery('.slide-selector>li').eq(s);
                var thisSlide = jQuery('.slide').eq(s);
                var htmlToInject = '<div class="mobile-only mobile-slide-seeker">' + thisSlideSeeker.html() + '</div>';
                jQuery(htmlToInject).insertBefore( thisSlide );
            }
        }

        /**
         * This is the new method of unserializing for Share links.
         */
        yawizard.setAllTags(objUrlQueries);
        
        /**
         * This is the original method of unserializing the answers from a GET query string.
         * This serves the Share-link bookmarks, which restore what the page looked like
         * at the time that the Share link is created.
         *
         * I'm leaving this active so that old bookmarks don't break. But this has
         * bugs and it is inherently hard to maintain, so it's deprecated.
         */
        // If values for questions are pre-set (in the URL-query), set the values in the DOM
        jQuery('.wizard .question-container').each( function () {
            var jqThis = jQuery(this);
            var questionNumericalID = jqThis.attr('questionNumericalID');
            var questionWiztag = jqThis.attr('wiztag');
            var questionInput = jqThis.find('input, select');
            if ( typeof objUrlQueries['nqid' + questionNumericalID] != 'undefined' ) {
                var setValue = objUrlQueries['nqid' + questionNumericalID];
                if ( questionInput.get(0).tagName.toLowerCase() == 'select' ) {
                    questionInput.val(setValue);
                } else {
                    if ( questionInput.attr('type') == 'text' ) {
                        questionInput.val(setValue);
                    } else {
                        if ( parseInt(setValue) == 1 ) {
                            questionInput.get(0).checked = true;
                        } else {
                            questionInput.get(0).checked = false;
                        }
                    }
                }
            }
        });
        // Original unserialization routine ends here.

        yawizard.untouchAllSlidesDownTo(0);
        yawizard.seek(yawizard.currentActiveSlide, 1);
        yawizard.validateDependencyOnQuestions();
        yawizard.validateDependencyOnSlides();
        yawizard.updateSlideCountClasses();
        
        // Watch for changing wizard-step-id-# numbers in the hash of the URL in the address-bar, seek when appropriate
        setInterval( function () {
            yawizard.seekToHashInAddressBar()
        }, 250);
        
    },
    
    reimplementNavigationSlider: function() {
        
        if ( jQuery('.slide-selector-container').is(':visible') != true ) {
            setTimeout( function () {
                consoleLog('/* Coder Bookmark: CB-ZQKG799-BC */');
                yawizard.reimplementNavigationSlider();
            }, 250);
            return;
        }
        
        if ( typeof jQuery('.slide-selector-container').mCustomScrollbar != 'undefined' ) {
        // First, if the mCustomScrollbar lib is already implemented, destroy it (clean up from last implementation)
            jQuery('.slide-selector-container').mCustomScrollbar("destroy");
        }
        
        // Calculate the width of all visible <LI> elements within the slide-selector-container div
        var visibleSlideSelectorTotalLength = yawizard.getSlideSelectorsWidth();
        
        /* Bug killer - force the parent of the slide-selectors (top navigation <LI>s) to be a width wide enough to 
        contain all the child <LI> elements without them wrapping. This needs to be done before the invocation 
        of the mCustomScrollbar library */
        jQuery('.slide-selector-container').css('width', visibleSlideSelectorTotalLength + 10);
        
        /* Bug killer for FireFox - For the UL container of slide-selectors to be a width wide enough to contain all
        the child <LI> elements without them wrapping */
        jQuery('.slide-selector-container ul').css('width', visibleSlideSelectorTotalLength + 10);
        
        // If we are not in mobile mode, and sliderInNavigation is set, use the mCustomScrollbar lib...
        if ( jQuery(window).width() > 768 ) {
            
            var tabsBorder = 20;
            var spaceTaken = jQuery('.wizard-title').outerWidth(true) + jQuery('.wizard-back').outerWidth(true) + jQuery('.wizard-next').outerWidth(true) + tabsBorder;
            spaceAvailable = jQuery('.wizardHeader').width() - spaceTaken;
            spaceAvailable = ( Math.floor(spaceAvailable / 10) * 10 ) - 5;
            yawizard.consoleLog('spaceAvailable is ' + spaceAvailable);
            
            // Only implement the mCustomScrollbar lib if it is nessesary (if we cant fit all the slide-selectors within the parent)
            if ( visibleSlideSelectorTotalLength > spaceAvailable ) {
                
                jQuery('.slide-selector-container').mCustomScrollbar({
                    horizontalScroll: true,
                    scrollButtons:{
                        enable:true
                    }
                });
            
                setTimeout( function () {
                    jQuery(".slide-selector-container").css('width', spaceAvailable);
                    jQuery(".slide-selector-container").mCustomScrollbar("scrollTo", ".slide-selector-" + yawizard.currentActiveSlide, {
                        scrollInertia: 0
                    });
                }, 75);
                
            }
        }
    },
    
    getSlideSelectorsWidth: function () {
        
        var totalWidth = 0;
        var visibleSlideSelectors = jQuery('.slide-selector-container li:visible');
        for ( var x = 0 ; x < visibleSlideSelectors.length ; x++ ) {
            totalWidth += visibleSlideSelectors.eq(x).outerWidth(true);
        }
        
        return totalWidth;
    },
    
    back: function () {
    
        if ( yawizard.currentActiveSlide == 0 ) { return; } 
        var slideToId = yawizard.getNeighboringVisibleSlideId(yawizard.currentActiveSlide, false);
        if ( slideToId !== false ) {
            yawizard.seek( slideToId );
        } else {
            alert('There is no previous slide in this wizard, please select [a different] option(s).');
        }
        createFlyoutNotification();
        yawizard.detectThankYouSlide();
    },
    
    next: function () {
    
        var slideToId = yawizard.getNeighboringVisibleSlideId(yawizard.currentActiveSlide, true);
        if ( slideToId != false ) {
            yawizard.seek( slideToId );
        } else {
            if ( typeof yawizard['slides'][yawizard['currentActiveSlide']]['noNextSlideMessage'] != 'undefined' ) {
                var msg = yawizard['slides'][yawizard['currentActiveSlide']]['noNextSlideMessage'];
                jQuery('.alert-text').html(msg);
                jQuery.colorbox({
                    html: $('.vendorLightBox').html(),
                    overlayClose: false,
                    escKey: false

                });
            } else {
                var msg = "There is no next slide in this wizard, please select [a different] option(s).";
                jQuery('.alert-text').html(msg);
                if ( typeof jQuery.colorbox == 'undefined' ) {
                    alert(msg);
                } else {
                    jQuery.colorbox({
                        html: $('.vendorLightBox').html(),
                        overlayClose: true,
                        escKey: true
                    });
                }
            }
        }
        createFlyoutNotification();

        var slideLinkText = jQuery('.wizard-uniqueid-selectusa li.slide-current a').text().trim();
        if (slideLinkText == 'Thank You') {
            jQuery('.slide-current a.wizard-continue').hide();
        }
        yawizard.detectThankYouSlide();
    },


    
    getNeighboringVisibleSlideId: function(fromSlideId, seekNext) {
        
        var slideSeekers = jQuery('.slide-selector>li');
        
        if ( seekNext ) {
        
            for ( var sid = fromSlideId + 1 ; sid < yawizard.maxSlideCount ; sid++ ) {
                if ( slideSeekers.eq(sid).css('display') != 'none' ) return sid;
            }
            
        } else {
            
            for ( var sid = fromSlideId - 1 ; sid > -1 ; sid-- ) {
                if ( slideSeekers.eq(sid).css('display') != 'none' ) return sid;
            }
            if ( yawizard.slides[0]['title'] == 'Get Started' ) {
                return 0;
            }
        }
        
        return false;
    },
    
    seek: function (toSlideId, fastAndNoHook) {
        
        // Scroll to top of page
        window.scrollTo(0,0);
        
        var slideFrom = jQuery('.slide').eq(yawizard.currentActiveSlide);
        var slideTo = jQuery('.slide-id-' + toSlideId);
        yawizard.currentActiveSlide = toSlideId;
        
        // If the wizard has overridden the getShareURL() function, restore it
        if ( typeof getShareURL_original != 'undefined' ) {
            getShareURL = getShareURL_original;
        }
        if ( typeof haveWizardShareURL != 'undefined' ) {
            haveWizardShareURL = undefined;
        }
        
        // Reset the slide-current class
        jQuery('.slide').removeClass('slide-current');
        jQuery('.slide-selector>li').removeClass('slide-current');
        jQuery('.mobile-slide-seeker').removeClass('slide-current');
        jQuery('.slide').eq(toSlideId).addClass('slide-current');
        jQuery('.slide-selector>li').eq(toSlideId).addClass('slide-current');
        jQuery('.mobile-slide-seeker').eq(toSlideId).addClass('slide-current');
        jQuery('body').removeClass('wizard-ajax-complete');
        
        // Update the current-slide-is-# class on the wizard's container
        for ( var x = 0 ; x < yawizard.maxSlideCount ; x++ ) {
            jQuery('.wizard.wizard-container').removeClass('current-slide-is-' + x);
            jQuery('body').removeClass('wizard-current-slide-is-' + x);
        }
        jQuery('.wizard.wizard-container').addClass('current-slide-is-' + yawizard.currentActiveSlide);
        jQuery('body').addClass('wizard-current-slide-is-' + yawizard.currentActiveSlide);
        
        // Update touched/untouched classes, and question/slide visibility-dependencies 
        yawizard.touchAllSlidesUpTo(toSlideId, true);
        yawizard.validateDependencyOnSlides();
        yawizard.validateDependencyOnQuestions();
        
        // Update the hash in the URL to wizard-step-id-#
        if ( yawizard.currentActiveSlide == 0 ) {
            document.location.hash = '';
        } else {
            document.location.hash = 'wizard-step-id-' + yawizard.currentActiveSlide;
        }
        
        // If this slide is set to pull content from an external source, trigger the ajax call
        if ( typeof yawizard['slides'][toSlideId]['ajaxLoad'] != 'undefined' && yawizard['slides'][toSlideId]['ajaxLoad'] != '' ) {
            slideTo.addClass('uses-ajax');
            
            var ajaxContainer = slideTo.find('.ajax-container');
            if ( typeof yawizard.originalSpinner == 'undefined' ) {
                yawizard.originalSpinner = ajaxContainer.html();
            } else {
                ajaxContainer.html(yawizard.originalSpinner);
            }
            
            ajaxContainer.show();
            ajaxContainer.removeClass('ajax-unused');
            ajaxContainer.addClass('ajax-inuse');
            ajaxContainer.addClass('ajax-loading');
            jQuery('body').addClass('wizard-ajax-loading');
            var pullFromURL = yawizard['slides'][toSlideId]['ajaxLoad'];
            
            var dataToSend = {
                'wizard_selected_tags':  JSON.stringify( yawizard.getAllSelectedTags() ),
                'allTags': yawizard.getValidTags(),
                'inputs': yawizard.getValidTags()
            };
            
            jQuery.post(pullFromURL, dataToSend, function(data) {
                ajaxContainer.hide();
                ajaxContainer.html( data );
                ajaxContainer.removeClass('ajax-loading');
                jQuery('body').removeClass('wizard-ajax-loading');
                ajaxContainer.addClass('ajax-complete');
                jQuery('body').addClass('wizard-ajax-complete');
                ajaxContainer.fadeIn();
            });
        }
        
        if ( fastAndNoHook == 1 ) {
            jQuery('.slide').hide();
            yawizard.validateDependencyOnNavigation();
            if ( jQuery(".slide-selector-container.mCustomScrollbar").length > 0 ) {
                jQuery(".slide-selector-container").mCustomScrollbar("scrollTo", ".slide-selector-" + yawizard.currentActiveSlide);
            }
            slideTo.show();
        } else {
            yawizard.hook_slide_transition(slideFrom, slideTo, function (verifyProperVisibility) {
                if ( typeof verifyProperVisibility == 'undefined' ) {
                    verifyProperVisibility = true;
                }
                if ( verifyProperVisibility ) {
                    jQuery('.slide').hide();
                }
                yawizard.validateDependencyOnNavigation();
                if ( verifyProperVisibility ) {
                    slideTo.show();
                }
                if ( jQuery(".slide-selector-container.mCustomScrollbar").length > 0 ) {
                    jQuery(".slide-selector-container").mCustomScrollbar("scrollTo", ".slide-selector-" + yawizard.currentActiveSlide);
                }
            });
        }
        createFlyoutNotification();
        yawizard.detectThankYouSlide();
    },
    
    seekToHashInAddressBar: function () {
        
        if ( typeof lastHashCheck == 'undefined' ) {
            lastHashCheck = document.location.hash;
        }
        
        if ( lastHashCheck != document.location.hash ) {
            if ( String(document.location.hash).indexOf('wizard-step-id') == -1 ) {
                consoleLog('The [wizard-slide-id] hash in the addressbar has changed, but is unrecongnizable');
            } else {
                seekToSlide = String(document.location.hash).split('-');
                seekToSlide = seekToSlide[seekToSlide.length - 1];
                seekToSlide = parseInt(seekToSlide);
                if ( isNaN(seekToSlide) ) {
                    if ( seekToSlide != yawizard.currentActiveSlide ) {
                        consoleLog('The [wizard-slide-id] hash in the addressbar has changed, now seeking the wizard to slide ' + seekToSlide);
                        yawizard.seek(seekToSlide);
                    }
                }
            }
        }
        
        lastHashCheck = document.location.hash;
    },
    
    touchAllSlidesUpTo: function (slideId) {
        for ( var s = 0 ; s < slideId ; s++ ) {
            yawizard.setSlideTouchState(s, true);
        }
        yawizard.setSlideTouchState(slideId, true);
    },
    
    untouchAllSlidesDownTo: function (slideId) {
        for ( var s = yawizard.maxSlideCount ; s > slideId ; s-- ) {
            yawizard.setSlideTouchState(s, false);
        }
        yawizard.setSlideTouchState(slideId, false);
    },
    
    setSlideTouchState: function (slideId, isTouched) {
        if ( isTouched ) {
            jQuery('.slide-selector>li').eq(slideId).removeClass('untouched');
            jQuery('.slide-selector>li').eq(slideId).addClass('touched');
            jQuery('.slide').eq(slideId).removeClass('untouched');
            jQuery('.slide').eq(slideId).addClass('touched');
            jQuery('.mobile-slide-seeker').eq(slideId).removeClass('untouched');
            jQuery('.mobile-slide-seeker').eq(slideId).addClass('touched');
            var prevVisibleSlideId = yawizard.getNeighboringVisibleSlideId(slideId, false);
            if ( prevVisibleSlideId !== false ) {
                jQuery('.slide-selector>li').eq(prevVisibleSlideId).removeClass('next-visible-slide-is-untouched');
                jQuery('.slide-selector>li').eq(prevVisibleSlideId).addClass('next-visible-slide-is-touched');
                jQuery('.slide').eq(prevVisibleSlideId).removeClass('next-visible-slide-is-untouched');
                jQuery('.slide').eq(prevVisibleSlideId).addClass('next-visible-slide-is-touched');
                jQuery('.mobile-slide-seeker').eq(prevVisibleSlideId).removeClass('next-visible-slide-is-untouched');
                jQuery('.mobile-slide-seeker').eq(prevVisibleSlideId).addClass('next-visible-slide-is-touched');
            }
        } else {
            jQuery('.slide-selector>li').eq(slideId).removeClass('touched');
            jQuery('.slide-selector>li').eq(slideId).addClass('untouched');
            jQuery('.slide').eq(slideId).removeClass('touched');
            jQuery('.slide').eq(slideId).addClass('untouched');
            jQuery('.mobile-slide-seeker').eq(slideId).removeClass('touched');
            jQuery('.mobile-slide-seeker').eq(slideId).addClass('untouched');
            var prevVisibleSlideId = yawizard.getNeighboringVisibleSlideId(slideId, false);
            if ( prevVisibleSlideId !== false ) {
                jQuery('.slide-selector>li').eq(prevVisibleSlideId).removeClass('next-visible-slide-is-touched');
                jQuery('.slide-selector>li').eq(prevVisibleSlideId).addClass('next-visible-slide-is-untouched');
                jQuery('.slide').eq(prevVisibleSlideId).removeClass('next-visible-slide-is-touched');
                jQuery('.slide').eq(prevVisibleSlideId).addClass('next-visible-slide-is-untouched');
                jQuery('.mobile-slide-seeker').eq(prevVisibleSlideId).removeClass('next-visible-slide-is-touched');
                jQuery('.mobile-slide-seeker').eq(prevVisibleSlideId).addClass('next-visible-slide-is-untouched');
            }
        }
    },
    
    event_input_element_change: function() {
        setTimeout( function () {
            yawizard.validateDependencyOnQuestions();
            yawizard.validateDependencyOnSlides();
            yawizard.updateSelectedTagClasses();
        }, 10);
    },
    
    validateDependencyOnNavigation: function () {
        
        // We do not show the normal navigation in the mobile layout
        if ( jQuery(window).width() < 768 ) {
            jQuery('.wizardHeader>.tabs').css('display', 'none');
            return false;
        }
        
        // Validate [visibility] dependency on the [entire] navigational bar
        if ( yawizard.dependencyIsMet(yawizard['navigationDependencyLogic']) ) {
            jQuery('.wizard-container').removeClass('navigation-is-hidden');
            jQuery('.wizard-container>.wizardHeader').not(':visible').fadeIn();
        } else {
            jQuery('.wizard-container>.wizardHeader:visible').fadeOut( function () {
                jQuery('.wizard-container').addClass('navigation-is-hidden');
            });
        }
        
    },
    
    validateDependencyOnSlides: function () {
        
        // Validate [visibility] dependency on each slide-seeker
        for ( var slideKey in yawizard['slides'] ) {
        
            var slide = yawizard['slides'][slideKey];
            var slideUiElement = jQuery('.slide-selector>li').eq(slideKey);
            var slideUiElementMobile = jQuery('.mobile-slide-seeker').eq(slideKey);
            var slideUiIsVisible = ( slideUiElement.css('display') != 'none' );
            
            
            if ( typeof slide['dependencyLogic'] != 'undefined' ) {
            
                var shouldBeVisible = yawizard.dependencyIsMet( slide['dependencyLogic'] );
                
                if ( slideUiIsVisible == true && shouldBeVisible == false ) {
                    yawizard.updateSlideCountClasses();
                    yawizard.hook_slideseeker_hide_transition(slideUiElement, function () {
                        slideUiElement.hide();
                        yawizard.updateSlideCountClasses();
                    });
                } else if ( slideUiIsVisible == false && shouldBeVisible == true ) {
                    yawizard.updateSlideCountClasses();
                    yawizard.hook_slideseeker_show_transition(slideUiElement, function () {
                        slideUiElement.show();
                        yawizard.updateSlideCountClasses();
                    });
                }
                
                if ( slideUiElementMobile.length > 0 ) {
                    var slideUiMobileIsVisible = ( slideUiElementMobile.css('display') != 'none' );
                    if ( slideUiMobileIsVisible == true && shouldBeVisible == false ) {
                        yawizard.updateSlideCountClasses();
                        yawizard.hook_slideseeker_hide_transition(slideUiElementMobile, function () {
                            slideUiElementMobile.hide();
                            yawizard.updateSlideCountClasses();
                        });
                    } else if ( slideUiMobileIsVisible == false && shouldBeVisible == true ) {
                        yawizard.updateSlideCountClasses();
                        yawizard.hook_slideseeker_show_transition(slideUiElementMobile, function () {
                            slideUiElementMobile.show();
                            yawizard.updateSlideCountClasses();
                        });
                    }
                }
                
            }
        }
        
    },
    
    updateSlideCountClasses: function () {
        
        // Remove old slide-count class identifiers
        for ( var x = 0 ; x < 20 ; x++ ) {
            jQuery('.slide-selector').removeClass('slide-count-' + x);
            jQuery('.wizardHeader>.tabs').removeClass('slides-shown-count-' + x);
        }
        
        // Add new slide-count class identifier
        var visibleSlides = jQuery('.slide-selector>li').filter(function() { return $(this).css("display") != "none" });
        var slideCount = visibleSlides.length;
        jQuery('.slide-selector').addClass('slide-count-' + slideCount);
        jQuery('.wizardHeader>.tabs').addClass('slides-shown-count-' + slideCount);
        
        // Add first/last slide identifier
        jQuery('.slide-selector>li').removeClass('slide-first-visible');
        jQuery('.slide-selector>li').removeClass('slide-last-visible');
        visibleSlides.first().addClass('slide-first-visible');
        visibleSlides.last().addClass('slide-last-visible');
        
        // Reimplement the slide since the visible slide count has changed
        yawizard.reimplementNavigationSlider();
    },
    
    validateDependencyOnQuestions: function (inSlideId) {
        
        // inSlideId shall default to the value of currentActiveSlide
        if ( typeof inSlideId == 'undefined' ) {
            inSlideId = yawizard['currentActiveSlide'];
        }
        
        var questions = yawizard['slides'][inSlideId]['questions'];
        for ( var questionKey in questions) {
            var question = questions[questionKey];
            var questionUiContainer = jQuery('.question-container.wiztag-' + question['wizardTag']);
            var questionUiIsVisible = ( questionUiContainer.css('display') != 'none' );
            var shouldBeVisible = yawizard.dependencyIsMet( question['dependencyLogic'] );
            if ( questionUiIsVisible == true && shouldBeVisible == false ) {
                consoleLog('Hiding question ' + question['wizardTag']);
                yawizard.hook_question_hide_transition(questionUiContainer, function () {
                    
                });
            } else if ( questionUiIsVisible == false && shouldBeVisible == true ) {
                consoleLog('Showing question ' + question['wizardTag']);
                yawizard.hook_question_show_transition(questionUiContainer, function () {
                    
                });
            }
        }
        
    },
    
    updateSelectedTagClasses: function () {
        
        // Wipe all selected-tag-* classes from the wizard container
        var currentClasses = jQuery('.wizard').attr('class').split(' ');
        for ( var x = 0 ; x < currentClasses.length ; x++ ) {
            if ( currentClasses[x].indexOf('selected-tag-') != -1 ) {
                currentClasses[x] = '';
            }
        }
        var newClasses = currentClasses.join(' ');
        newClasses = jQuery.trim( newClasses );
        jQuery('.wizard').attr('class', newClasses);
        
        // Wipe all selected-tag-* classes from the wizard container's parent
        var currentClasses = jQuery('.wizard').parent().attr('class').split(' ');
        for ( var x = 0 ; x < currentClasses.length ; x++ ) {
            if ( currentClasses[x].indexOf('selected-tag-') != -1 ) {
                currentClasses[x] = '';
            }
        }
        var newClasses = currentClasses.join(' ');
        newClasses = jQuery.trim( newClasses );
        jQuery('.wizard').parent().attr('class', newClasses);
        
        // Add all nessesary selected-tag-* classes to the wizard container and the wizard container's parent
        var selectedTags = yawizard.getAllSelectedTags();
        for ( var tag in selectedTags ) {
            jQuery('.wizard').addClass('selected-tag-' + tag);
            jQuery('.wizard').parent().addClass('selected-tag-' + tag);
        }
        
    },
    
    getAllSelectedTags: function () {
        var allSelectedTags = {};
        jQuery('.wizard input:checked, .wizard option:checked').each( function () {
            var thisId = jQuery(this).attr('id');
            thisId = String(thisId).replace('wiztag-', '')
            allSelectedTags[thisId] = thisId;
        });
        return allSelectedTags;
    },

    setAllTags: function (allTags) {
        var i;
        (function($) {
          for(i in allTags) {
            if(allTags.hasOwnProperty(i)) {
              var jqElement = $('#wiztag-' + i);

              if(jqElement.is('input[type="radio"], input[type="checkbox"]')) {
                if(allTags[i] == 1) {
                  jqElement.prop('checked', true)
                }
              }
              else {
                jqElement.val(allTags[i]);
              }
            }
          }

          // NJB -- This clashes with code elsewhere. To do -- make sure pretty checkboxes
          // look right.
          //$('.has-pretty-child a').removeClass('checked');
          //$('.has-pretty-child input:checked + a').addClass('checked');
        })(jQuery);
    },

    getValidTags: function () {
      consoleLog('Locating valid wiztags:');
      var allTags = {};
      var visibleSlides = $('.slide-selector > li').filter(function() { return $(this).css("display") != "none" });
      visibleSlides.each(function() {
        var classList = $(this).attr('class').split(/\s+/);
        $.each(classList, function() {
          var s = this.replace(/^slide-selector-/, "");
          if(this != s) {
            var slideNum = s;
            var slideSelector = ".slide-id-" + slideNum;

            jQuery(slideSelector + ' input, ' + slideSelector + ' option, ' + slideSelector + ' select, ' + slideSelector + ' textarea').each( function () {
                var jqThis = jQuery(this);
                var thisId = jqThis.attr('id');
                thisId = String(thisId).replace('wiztag-', '');
                if ( jqThis.attr('type') == 'text' ) {
                    allTags[thisId] = jqThis.val();
                } else if ( jqThis.get(0).tagName.toLowerCase() == 'select' || jqThis.get(0).tagName.toLowerCase() == 'textarea' ) {
                    allTags[thisId] = jqThis.val();
                } else {
                    if ( jqThis.is(':checked') ) {
                        allTags[thisId] = 1;
                    } else {
                        allTags[thisId] = 0;
                    }
                }
                consoleLog('Slide ' + slideNum + ': ' + thisId + ' = ' + allTags[thisId]);
            });
          }
        });
      });
      consoleLog('Done.');
      return allTags;
    },
    
    getAllTags: function () {
        var allTags = {};
        jQuery('.wizard input, .wizard option, .wizard select, .wizard textarea').each( function () {
            var jqThis = jQuery(this);
            var thisId = jqThis.attr('id');
            thisId = String(thisId).replace('wiztag-', '');
            if ( jqThis.attr('type') == 'text' ) {
                allTags[thisId] = jqThis.val();
            } else if ( jqThis.get(0).tagName.toLowerCase() == 'select' || jqThis.get(0).tagName.toLowerCase() == 'textarea' ) {
                allTags[thisId] = jqThis.val();
            } else {
                if ( jqThis.is(':checked') ) {
                    allTags[thisId] = 1;
                } else {
                    allTags[thisId] = 0;
                }
            }
        });
        return allTags;
    },
    
    getShareLink: function() {
        var currentWizTagQuestionValues = yawizard.getValidTags();
        var requestArr = [];
        jQuery('.wizard .question-container').each( function () {
            var jqThis = jQuery(this);
            var questionNumericalID = jqThis.attr('questionNumericalID');
            var questionWiztag = jqThis.attr('wiztag');
            var questionInput = jqThis.find('input, select');
            if ( questionInput.length > 0 ) {
                requestArr[questionNumericalID] = 'nqid' + questionNumericalID + '=' + escape( currentWizTagQuestionValues[questionWiztag] );
            }
        });
        return document.location.protocol + '//' + document.location.host + document.location.pathname + '?' + jQuery.param(currentWizTagQuestionValues) + '#wizard-step-id-' + yawizard.currentActiveSlide;
    },
    
    dependencyIsMet: function (logicArray, mode) {
        
        // if logicArray is really a string, then we assume this to be a wizard-tag, and check if the input associated with the given WizardTag is selected/checked or not
        if ( typeof logicArray == "string" ) {
            
            if ( jQuery.trim(logicArray) == "" ) {
                return true;
            }
            
            if ( logicArray.indexOf('+') != -1 ) {
            
                mode = 'and';
                logicArray = logicArray.split('+');
                
            } else {
            
                var ret = null;
                
                // Determin if the return if this logic should be inverted
                var targetWizTag = logicArray;
                if ( targetWizTag.substring(0,1) == "!" ) {
                    var notOperation = true;
                    targetWizTag = targetWizTag.substring(1);
                } else {
                    var notOperation = false;
                }
                
                // If the wizard-tag is "slide#" (like slide0, slide1, slide2, etc.), then this is a special case
                if ( targetWizTag.substring(0, 5) == 'slide' && !isNaN(targetWizTag.substring(5) ) ) {
                    var testSlideId = parseInt(targetWizTag.substring(5));
                    ret = ( testSlideId == yawizard.currentActiveSlide );
                    if ( notOperation ) { ret = !ret };
                    return ret;
                }
                
                // Locate the input UI-element within the wizard
                var uiElement = jQuery("#wiztag-" + targetWizTag);
                if ( typeof uiElement == 'undefined' || typeof uiElement.get(0) == 'undefined' ) {
                    consoleLog('Error: dependencyIsMet() could not find "#wiztag-' + targetWizTag + '". Coder Bookmark: CB-X4S363W-BC');
                    return false;
                }
                var uiEleTagName = uiElement.get(0).tagName.toLowerCase();
                var uiEleType = String(uiElement.attr("type")).toLowerCase();
                
                // Based on the input-UI type, check the element's value
                switch (uiEleTagName + "_" + uiEleType) {
                    case "input_checkbox":
                    case "input_radio":
                    case "option_undefined":
                        ret = uiElement.is(':checked');
                        break;
                    case "input_text":
                        if ( jQuery.trim(uiElement.val()) == "" ) {
                            ret = false;
                        } else {
                            ret = true;
                        }
                        break;
                    default:
                        alert("Error: Coder Bookmark: CB-2QWQBQ1-BC");
                }
                
                if ( notOperation ) { ret = !ret };
                return ret;
            }
        }
        
        // we shall assume "AND" logic by default
        if ( typeof mode == "undefined" ) {
            mode = "and";
        }
        
        var resolved = [];
        for ( var objKey in logicArray) {
            if ( !isNaN(objKey) ) {
                var resolveWithMode = mode;
            } else {
                var resolveWithMode = objKey;
            }
            resolved.push( yawizard.dependencyIsMet(logicArray[objKey], resolveWithMode) );
        }
        
        if ( mode == "and" ) {
        
            for ( var resKey in resolved) {
                if ( resolved[resKey] == false ) {
                    return false;
                }
            }
            return true;
            
        } else if ( mode == "or" ) {
        
            for ( var resKey in resolved) {
                if ( resolved[resKey] == true ) {
                    return true;
                }
            }
            return false;
            
        } else {
            alert('Error: Coder Bookmark: CB-3L7OOGY-BC');
        }
        
    },
    
    /**  void consoleLog(inptMsg)
      *  Executes console.log with given parameter if console.log is defined
      */
    consoleLog: function (inptMsg) {
        if ( typeof console != 'undefined' ) {
            console.log(inptMsg);
        }
    },

    detectThankYouSlide: function () {
      setTimeout(function() {
        jQuery('.slide-selector > li').each(function() {
          if (jQuery(this).children('a').text().trim() == 'Thank You' && jQuery(this).hasClass('touched') && jQuery(this).is(':visible')) {
            if (jQuery(this).hasClass('slide-current')) {
              jQuery(this).css('background', 'transparent url("/sites/all/themes/bizusa/images/wizCurrentLast.png") no-repeat center 18px');
              jQuery('.slide-selector .slide-last-visible').hide();
              return false;
            }
            else {
              jQuery(this).css('background', 'transparent url("/sites/all/themes/bizusa/images/wizTouchedLast.png") no-repeat center 18px');
              jQuery('.slide-selector .slide-last-visible').hide();
              return false;
            }
          }
          else {
            if (jQuery('.slide-selector .slide-last-visible').is(':hidden')) {
              jQuery('.slide-selector .slide-last-visible').show();
            }
          }
        });
      }, (20));
    },
}
