// Global JavaScript variable to keep track what slide we are on
   currentSlide = [0, 0, 0];

/* JavaScript to intialize the Tour page */
jQuery(document).ready( function () {
// Set all 3 fo the slideshows to the first slide to active
tourSetActiveSlide(0, 0);
tourSetActiveSlide(1, 0);
tourSetActiveSlide(2, 0);
});

/* Misc */
function tourSetActiveSlide(slideshowIndex, slideId) {

// Validation
   if ( slideId < 0 ) {
    slideId = 0;
}

// Set all slides as inactive
jQuery('.busatour-mastercontainer-' + slideshowIndex + ' .busatour-slideshowarea-step-container').removeClass('active');
jQuery('.busatour-mastercontainer-' + slideshowIndex + ' .busatour-slideshowarea-slide-container').removeClass('active');

// Set target step and slide as active
jQuery('.busatour-mastercontainer-' + slideshowIndex + ' .busatour-slideshowarea-step-container').eq(slideId).addClass('active');
jQuery('.busatour-mastercontainer-' + slideshowIndex + ' .busatour-slideshowarea-slide-container').eq(slideId).addClass('active');

// Update the description
jQuery('.busatour-mastercontainer-' + slideshowIndex + ' .busatour-leftarea-text').text(jsonSlideshows[slideshowIndex][slideId]['description']);

// Update title text
   var newTitle = jsonSlideshows[slideshowIndex][slideId]['left-title'];
jQuery('.busatour-mastercontainer-' + slideshowIndex + ' .busatour-leftarea-title').text(newTitle);

// Update suibtitle text
   var newSubtitle = jsonSlideshows[slideshowIndex][slideId]['snippet'];
jQuery('.busatour-mastercontainer-' + slideshowIndex + ' .busatour-slideshowarea-controles-text').html(newSubtitle);

// Update the class-marker on the master-container
jQuery('.busatour-mastercontainer-' + slideshowIndex).removeClass('currentslide-id-0');
jQuery('.busatour-mastercontainer-' + slideshowIndex).removeClass('currentslide-id-1');
jQuery('.busatour-mastercontainer-' + slideshowIndex).removeClass('currentslide-id-2');
jQuery('.busatour-mastercontainer-' + slideshowIndex).removeClass('currentslide-id-3');
jQuery('.busatour-mastercontainer-' + slideshowIndex).removeClass('currentslide-id-4');
jQuery('.busatour-mastercontainer-' + slideshowIndex).addClass('currentslide-id-' + slideId);

// Note what slide we are showing in the global JavaScript variable, currentSlide
currentSlide[slideshowIndex] = slideId;

}

