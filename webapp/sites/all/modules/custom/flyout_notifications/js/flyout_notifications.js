// This code has been moved into global js in order to more easily include
// the notification code on all wizard pages.
(function($) {
  $(document).ready(function() {
    createFlyoutNotification();
  });

  // Hide the notification when the close button is clicked.
  $(document).on('click', '.recommended-flyout-close', function(e) {
    $('.recommended-flyout-wrap').parent().children(":visible").hide("slide", { direction: "right" }, 1000);
  });

  // Show the notification when the user scrolls to the bottom
  // of the page.
  $(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() == $(document).height()) {
      var flyoutWrapper = $('.recommended-flyout-wrap');
      if (!flyoutWrapper.is(':visible')) {
        flyoutWrapper.show("slide", { direction: "right" }, 1000);
      }
    }
  });

  function createFlyoutNotification() {
    // Remove any existing flyers.
    $('.recommended-flyout-container').remove();
    var path = window.location.pathname;
    var anchor = window.location.hash;
    if (anchor) {
      path += anchor;
    }
    conditions = {
      "entityCondition": [
        [ "entity_type", "node" ],
        [ "bundle", "recommended_flyout" ]
      ],
      "fieldCondition": [
        [ "field_display_page_path", "value", [path], "=" ]
      ]
    }
    entity_field_query_json(conditions, true).done(function(data) {
      if (data == 'No entities loaded.') {
        return;
      }
      $.each(data, function(key, value) {
        $.each(value, function(nid, node) {
          var now = new Date();
          var publishDate = (node.field_date_published.und) ? node.field_date_published.und[0].value : null;
          var unpublishDate = (node.field_unpublishing_date.und) ? node.field_unpublishing_date.und[0].value : null;
          publishDate = (publishDate) ? publishDate.substr(0, 10) : publishDate;
          unpublishDate = (unpublishDate) ? unpublishDate.substr(0, 10) : unpublishDate;
          if (publishDate) {
            publishDate = new Date(publishDate);
            if (publishDate > now) {
              // Publish date is in the future, don't do anything for now.
              return;
            }
          }
          if (unpublishDate) {
            unpublishDate = new Date(unpublishDate);
            if (unpublishDate < now) {
              // Unpublish date is in the past, don't do anything.
              return;
            }
          }

          // Unfortunately we have to render the teaser view to extract the full
          // image path because the node only provides the uri.
          entity_render_view('node', node.nid, 'teaser').done(function(data) {
            // We completed validation - extract the title and snippet
            // from the node.
            var title = node.title;
            var snippet = (node.field_snippet.und) ? node.field_snippet.und[0].value : '';
            var landingPage = node.field_landing_page_path.und[0].value;
            var img = $(data).find('img').attr('src');

            if (typeof img != 'undefined') {
              var html = '<div class="recommended-flyout-container">' +
                '<div class="recommended-flyout-wrap" style="width: 360px;">' +
                '<div class="recommended-flyout">' +
                '<a href="' + landingPage +'" class="recommended-flyout-link" target="_blank">' +
                '<img src="' + img + '" class="recommended-flyout-content-image" width="60" height="60">' +
                '<h6 class="recommended-flyout-heading">' + title + '</h6>' +
                '<h4 class="recommended-flyout-snippet">' + snippet + '</h4>' +
                '</a>' +
                '<span class="recommended-flyout-close">&#10006;</span>' +
                '</div>' +
                '</div>' +
                '</div>';
            }
            else {
              var html = '<div class="recommended-flyout-container">' +
                '<div class="recommended-flyout-wrap" style="width: 360px;">' +
                '<div class="recommended-flyout">' +
                '<a href="' + landingPage +'" class="recommended-flyout-link" target="_blank">' +
                '<h6 class="recommended-flyout-heading">' + title + '</h6>' +
                '<h4 class="recommended-flyout-snippet">' + snippet + '</h4>' +
                '</a>' +
                '<span class="recommended-flyout-close">&#10006;</span>' +
                '</div>' +
                '</div>' +
                '</div>';
            }

            $('#contentArea').append(html);

            $('.recommended-flyout-wrap').hide();
          });



          // We just need the first value, break out of the loop.
          return false;
        });
      });
    });
  }
})(jQuery);
