<?php

$slideshows = array();

$slideshows[] = array(
        array(
            "left-title" => "Tour Headline 1A",
            "description" => "This message is stored in tour/.page.php, you can edit these emssages there. If desiered you can hilight features of this site on this page.",
            "slide-title" => "Slide 1A Title",
            "snippet" => "Slide 1A subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/1a.jpg"
        ),
        array(
            "left-title" => "Tour Headline 1B",
            "description" => "You can edit the images shown to present screen-shots of this site as desiered from the tourImgs directory in sites/all/themes/bizusa/images. If you edit these images and do not see the images updating within your browser, then you need to flush your browser cache.",
            "slide-title" => "Slide 1B Title",
            "snippet" => "Slide 1B subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/1b.jpg"
        ),
        array(
            "left-title" => "Tour Headline 1C",
            "description" => "If you have no need for a Tour page, then you will want to delete the tour directoy from sites/all/page, and then you remove the tour page from the footer/menu.",
            "slide-title" => "Slide 1C Title",
            "snippet" => "Slide 1C subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/1c.jpg"
        ),
        array(
            "left-title" => "Tour Headline 1D",
            "description" => "If you have no need for a Tour page, then you will want to delete the tour directoy from sites/all/page, and then you remove the tour page from the footer/menu.",
            "slide-title" => "Slide 1D Title",
            "snippet" => "Slide 1D subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/1d.jpg"
        )
    );
    $slideshows[] = array(
        array(
            "left-title" => "Tour Headline 2A",
            "description" => "This message is stored in tour/.page.php, you can edit these emssages there. If desiered you can hilight features of this site on this page.",
            "slide-title" => "Slide 2A Title",
            "snippet" => "Slide 2A subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/2a.jpg"
        ),
        array(
            "left-title" => "Tour Headline 2B",
            "description" => "You can edit the images shown to present screen-shots of this site as desiered from the tourImgs directory in sites/all/themes/bizusa/images. If you edit these images and do not see the images updating within your browser, then you need to flush your browser cache.",
            "slide-title" => "Slide 2B Title",
            "snippet" => "Slide 2B subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/2b.jpg"
        ),
        array(
            "left-title" => "Tour Headline 2C",
            "description" => "If you have no need for a Tour page, then you will want to delete the tour directoy from sites/all/page, and then you remove the tour page from the footer/menu.",
            "slide-title" => "Slide 2C Title",
            "snippet" => "Slide 2C subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/2c.jpg"
        ),
        array(
            "left-title" => "Tour Headline 2D",
            "description" => "If you have no need for a Tour page, then you will want to delete the tour directoy from sites/all/page, and then you remove the tour page from the footer/menu.",
            "slide-title" => "Slide 2D Title",
            "snippet" => "Slide 2D subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/2d.jpg"
        )
    );
    $slideshows[] = array(
        array(
            "left-title" => "Tour Headline 3A",
            "description" => "This message is stored in tour/.page.php, you can edit these emssages there. If desiered you can hilight features of this site on this page.",
            "slide-title" => "Slide 3A Title",
            "snippet" => "Slide 3A subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/3a.jpg"
        ),
        array(
            "left-title" => "Tour Headline 3B",
            "description" => "You can edit the images shown to present screen-shots of this site as desiered from the tourImgs directory in sites/all/themes/bizusa/images. If you edit these images and do not see the images updating within your browser, then you need to flush your browser cache.",
            "slide-title" => "Slide 3B Title",
            "snippet" => "Slide 3B subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/3b.jpg"
        ),
        array(
            "left-title" => "Tour Headline 3C",
            "description" => "If you have no need for a Tour page, then you will want to delete the tour directoy from sites/all/page, and then you remove the tour page from the footer/menu.",
            "slide-title" => "Slide 3C Title",
            "snippet" => "Slide 3C subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/3c.jpg"
        ),
        array(
            "left-title" => "Tour Headline 3D",
            "description" => "If you have no need for a Tour page, then you will want to delete the tour directoy from sites/all/page, and then you remove the tour page from the footer/menu.",
            "slide-title" => "Slide 3D Title",
            "snippet" => "Slide 3D subtitle",
            "slide-img-src" => "/sites/all/themes/bizusa/images/tourImgs/3d.jpg"
        )
    );

/* Expose the php-variable tourSlides to a JavaScript variable */
    $jsonSlideshows = json_encode($slideshows);
    drupal_add_js("jsonSlideshows = " . $jsonSlideshows . ";", array('weight' => 100, 'type' => 'inline', 'group' => JS_THEME));

if ( strpos(request_uri(), '-DEBUG-') !== false ) {
        dsm(
            array(
                'slideshows' => $slideshows,
                'jsonSlideshows' => $jsonSlideshows
            )
        );
    }
?>
 <div class="busaalltours-mastercontainer-denied">
    This page is not available on your tablet or mobile size
    screens. <?php print(l(t('Go Back Home'), '<front>')); ?>
</div>
<div class="busaalltours-mastercontainer">
    <?php foreach ( $slideshows as $slideshowIndex => $slideshow ) : ?>
    <div class="busatour-mastercontainer busatour-mastercontainer-<?php print $slideshowIndex; ?>">

        <!-- LEFT AREA -->
        <div class="busatour-leftarea">
            <div class="busatour-leftarea-title">
                <!-- This area gets erased and populated by JavaScript -->
            </div>
        <div class="busatour-leftarea-text">
            <!-- This area gets erased and populated by JavaScript -->
        </div>
    </div>

    <!-- SLIDESHOW AREA -->
    <div class="busatour-slideshowarea">
        <div class="busatour-slideshowarea-steps-container">
        <?php foreach ( $slideshow as $slideIndex => $tourSlide ):  ?>
            <div class="busatour-slideshowarea-step-container busatour-slideshowarea-step-container-<?php print $slideIndex; ?>">
                <div class="busatour-slideshowarea-step-textcontainer">
                    <div class="busatour-slideshowarea-step-text">
                        <input type="button" value="<?php print $tourSlide['slide-title']; ?>" onclick="tourSetActiveSlide(<?php print $slideshowIndex; ?>, <?php print $slideIndex; ?>);" />
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        </div>
         <div class="busatour-slideshowarea-slides-container">
            <?php foreach ( $slideshow as $tourSlide ): ?>
                <div class="busatour-slideshowarea-slide-container">
                    <img alt="" src="<?php print $tourSlide['slide-img-src']; ?>" />
                </div>
            <?php endforeach ?>
        </div>
        <div class="busatour-slideshowarea-controles-container">
            <div class="busatour-slideshowarea-controles-back">
                <div class="busatour-slideshowarea-controles-back-btn">
                    <input type="button" alt="Back" onclick="tourSetActiveSlide(<?php print $slideshowIndex; ?>, currentSlide[<?php print $slideshowIndex; ?>] - 1);" />
                </div>
            </div>
            <div class="busatour-slideshowarea-controles-text">
                <!-- This area gets erased and populated by JavaScript -->
            </div>
            <div class="busatour-slideshowarea-controles-next">
                <div class="busatour-slideshowarea-controles-next-btn">
                    <input type="button" alt="Next" onclick="tourSetActiveSlide(<?php print $slideshowIndex; ?>, currentSlide[<?php print $slideshowIndex; ?>] + 1);" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="busatour-postmastercontainer-spacer busatour-postmastercontainer-spacer-<?php print $slideshowIndex; ?>">

</div>
  <?php endforeach ?>
</div>



