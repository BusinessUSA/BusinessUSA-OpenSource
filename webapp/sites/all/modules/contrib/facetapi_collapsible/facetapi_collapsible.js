/*jslint vars: true, indent: 2, white: true */
/*global Drupal: true, jQuery: true */
(function ($) {
  "use strict";
  // This strange little function allows the "expanded" class to be added to or
  // removed from the passed in facet based on the passed in condition, which
  // corresponds to a configured setting.
  var facetCollapseExpanded = function ($facet, condition, operation, behavior) {
    var wrapper = $facet.find('.facet-collapsible-wrapper').get(0);
    if (wrapper) {
      var facetId = wrapper.id;
      facetId = facetId.replace('facet-collapsible-', '');
      facetId = facetId.replace(/-/g, '_');
      if (Drupal.settings.facetapi_collapsible[facetId]) {
        // We either need to check that the 'condition' in Drupal.settings DOES
        // hold for the given facet's configuration, or that it DOES NOT hold. The
        // 'operation' boolean tells us which.
        var cond = (Drupal.settings.facetapi_collapsible[facetId][condition] === operation);
        // Only add or remove the class if the condition has been satisfied.
        if (cond) {
          // behavior is either "addClass" or "removeClass"
          $facet[behavior]('expanded');
        }
      }
    }
  };

  /**
   * Behavior for collapsing faceted search lists.
   */
  Drupal.behaviors.facetapiCollapsible = {
    attach : function (context) {
      var i = 0;
      $('.facetapi-collapsible').once(function () {
        var $facet = $(this);
        if ($('.facetapi-active', this).size() > 0) {
          $(this).addClass('expanded active');
        }
        else {
          // Add the 'expanded' class to the facet if configured to do so.
          facetCollapseExpanded($facet, 'expand', 1, 'addClass');

          $('h2', this).each(function () {
            $(this).click(function () {
              $facet.siblings('.facetapi-collapsible:not(.active)').each(function () {
                // Remove the 'expanded' class from all other facets that haven't
                // been configured to stay open.
                facetCollapseExpanded($(this), 'keep_open', 0, 'removeClass');
              });
              $facet.toggleClass('expanded');
            });
          });
        }

        // check cookie
        var cookie = $.cookie('Facetapi.collapsible.expanded');
        cookie = $.parseJSON(cookie);
        if (!cookie) {
          cookie = {};
        }
        $('.facetapi-collapsible ul.facetapi-collapsible .item-list').once(function () {
          var $list = $(this);
          var parentwrapper = $list.closest('.facet-collapsible-wrapper');
          if (parentwrapper) {
            var parentfacetId = parentwrapper.attr('id');
            parentfacetId = parentfacetId.replace('facet-collapsible-', '');
            parentfacetId = parentfacetId.replace(/-/g, '_');
            if (Drupal.settings.facetapi_collapsible[parentfacetId] && Drupal.settings.facetapi_collapsible[parentfacetId].collapsible_children) {
              var $parentfacet = $($list.siblings('.facetapi-facet').get(0));

              $('a', $parentfacet).each(function () {
                if (!cookie || !cookie[parentfacetId] || (cookie[parentfacetId].indexOf($(this).attr('href')) < 0)) {
                  $(this).html('<span class="facetapi-collapsible-handle">+</span>&nbsp;' + $(this).html());
                  $('ul', $(this).closest('.facetapi-facet').siblings('.item-list')).first().removeClass('expanded');
                }
                else {
                  $(this).html('<span class="facetapi-collapsible-handle">-</span>&nbsp;' + $(this).html());
                  $(this).closest('.block-facetapi.facetapi-collapsible').addClass('expanded');
                }
              }).addClass('collapselink');

              // expand facet with active childs
              if ($('a.facetapi-active', $(this)).length) {
                $('ul', $(this).closest('div')).first().addClass('expanded');
              }

              $('a .facetapi-collapsible-handle', $parentfacet).click(function (event) {
                var $clickedlist = $('ul', $parentfacet.siblings('.item-list')).first();
                var $clickedlink = $(this).closest('a');
                $clickedlist.toggleClass("expanded");
                if (!cookie) {
                  cookie = {};
                }
                if (!cookie[parentfacetId]) {
                  cookie[parentfacetId] = [];
                }
                if ($clickedlist.hasClass('expanded')) {
                  $(this).html('-');
                  cookie[parentfacetId].push($clickedlink.attr('href'));
                }
                else {
                  $(this).html('+');
                  var index = cookie[parentfacetId].indexOf($clickedlink.attr('href'));
                  if (index !== -1) {
                    cookie[parentfacetId].splice(index, 1);
                  }
                }

                if (Drupal.settings.facetapi_collapsible[parentfacetId].keep_open === false) {
                  $('ul', $list.closest('li').siblings('li')).each(function () {
                    $(this).removeClass("expanded");
                    $('a .facetapi-collapsible-handle', $(this).closest('li')).html('+');
                    var index = cookie[parentfacetId].indexOf($('a', $(this).closest('li')).attr('href'));
                    if (index !== -1) {
                      cookie[parentfacetId].splice(index, 1);
                    }
                  });
                }
                $.cookie(
                  'Facetapi.collapsible.expanded',
                  JSON.stringify(cookie),
                  {
                    path: Drupal.settings.basePath,
                    expires: 1
                  }
                );
                event.preventDefault();
              });
            }
          }
        });
        i = i + 1;
      });
    }
  };
})(jQuery);