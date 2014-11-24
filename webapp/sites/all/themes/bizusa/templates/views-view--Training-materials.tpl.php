<?php

/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
?>

<!-- NOTE: The following div is rendered from <?php print basename(__FILE__); ?> -->
<div class="<?php print $classes; ?>" rendersource="<?php print basename(__FILE__); ?>">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
    <div class="view-filters">
        <?php
            //these mediatypes are just textfields
            $btns = array(
                "Chat"=>"/training-materials/chat",
                "Online Training"=>"/training-materials/online training",
                "Videos"=>"/training-materials/video",
                "View All"=>"/training-materials/"
            );
        ?>
        <ul class="training-materials-filter-list">
            <?php foreach($btns as $text => $link):
                $sel = "";
                if('/'.request_path()==$link)
                    $sel=' training-materials-filter-btn-active';
                ?>
                <li class="training-materials-filter-list-item"><a class="training-materials-filter-btn<?php echo $sel;?>" href="<?php echo $link;?>"><?php echo $text;?></a></li>
            <?php endforeach;?>
        </ul>
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>

<!-- NOTE: The following script is rendered from <?php print basename(__FILE__); ?> -->
<script type="text/javascript" rendersource="<?php print basename(__FILE__); ?>">

 (function ($) {

    //Removes media type header text
    $('th.views-field-field-mediatype').text('');

    //Changes media type text to img tags
    for (i = 0; i < $('table.views-table tbody tr').length; i ++) {
        var $selectedmedia = $('tr td.views-field-field-mediatype').eq(i);
        var mediatype = $selectedmedia.text().toLowerCase().replace(/ /g,'');
        var imgsource = "<img src='/sites/all/themes/bizusa/images/training/"+mediatype+".png' alt='"+mediatype+"'>";
        $selectedmedia.html(imgsource);
    }

    //Creates an accordion using table data
    $('div.view-content').append("<ul id='mobile-accordion'></ul>");
    for (i = 0; i < $('table.views-table tbody tr').length; i ++) {
        var $title = $('tr td.views-field-title').eq(i).text();
        var $agency = $('tr td.views-field-field-agency').eq(i).text();
        var $description = $('tr td.views-field-body').eq(i).html();
        var $link = $('tr td.views-field-title a').eq(i).attr('href');
        var mediatype = $('tr td.views-field-field-mediatype').eq(i).html();
        var mediatypetext = $('tr td.views-field-field-mediatype img').eq(i).attr('alt');

        var output = "<li><div class='title-field'>"+$title+"</div>";
        output += "<div class='training-content'><div class='agency-field'>"+$agency+"</div>";
        output += "<div class='description-field'>"+$description+"</div>";
        output += "<div class='clearfix'><div class='mediatype-field'>"+mediatype+"<span>"+mediatypetext+"</span></div>";
        output += "<div class='learnmore-field clearfix'><a href='"+$link+"'>Learn More</a></div></div>";
        output += "</div></li>";

        $('ul#mobile-accordion').append(output);
    }

    //enable accordion
    $("#mobile-accordion > li > div.title-field").click(function(){
        if(false == $(this).next().is(':visible')) {
            $('#mobile-accordion div.training-content').slideUp(300);
        }
        $(this).next().slideToggle(300);
    });

    //open first item
    $('#mobile-accordion div.training-content:eq(0)').show();

    $('#edit-field-searchtags-value-wrapper > div > div > fieldset > legend').click(function () {
      $('#edit-field-searchtags-value-wrapper > div > div > fieldset > legend > span > a').click();
    });
  })(jQuery);

 </script>
