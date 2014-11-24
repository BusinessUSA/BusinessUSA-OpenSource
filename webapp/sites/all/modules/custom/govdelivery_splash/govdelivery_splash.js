(function($) {
  // secure cookie functionality for the module
  $.cookie = function(key, value, options) {
    // key and at least value given, set cookie...
    if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {
      options = $.extend({}, options);

      if (value === null || value === undefined) {
        options.expires = -1;
      }

      if (typeof options.expires === 'number') {
        var days = options.expires, t = options.expires = new Date();
        t.setDate(t.getDate() + days);
      }

      value = String(value);

      return (document.cookie = [
        encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
        options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
        options.path ? '; path=' + options.path : '',
        options.domain ? '; domain=' + options.domain : '',
        options.secure ? '; secure' : ''
      ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var decode = options.raw ? function(s) { return s; } : decodeURIComponent;

    var pairs = document.cookie.split('; ');
    for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) {
      if (decode(pair[0]) === key) return decode(pair[1] || ''); // IE saves cookies with empty string as "c; ", e.g. without "=" as opposed to EOMB, thus pair[1] may be undefined
    }
    return null;
  };
  // On document ready.
  $(document).ready(function(){
    $('#govdelivery-email').unbind('keypress').bind('keypress', function(e) {
      if (e.keyCode==13) {
        $("#govdelivery-submit").trigger("mousedown");
        return false;
      }
    });

    // when the email link is clicked, display the panel
    $('.email-updates').click(function() {
      window.scrollTo(0,0);
      $('#block-govdelivery_splash-govdelivery_splash_block').fadeIn('slow');
      return false;
    });

    // when set the 'go away' cookie if no thanks is pushed for step one
    $('#govdelivery-no-thanks').click(function() {
        $('#block-govdelivery_splash-govdelivery_splash_block').fadeOut('slow');
        $.cookie('_sbav', 'value', { path: '/', expires: 365 * 5 });
        return false;
    });

    // close the window, but do not set the cookie - when the close window div is clicked
    $('#govdelivery-close-window').click(function() {
      $.cookie('_sbav', 'value', { path: '/', expires: 3 });
      $('#block-govdelivery_splash-govdelivery_splash_block').fadeOut('slow');
    });

    // ask me later, functionality added
    $('#govdelivery-ask-later').click(function() {
      $('#block-govdelivery_splash-govdelivery_splash_block').fadeOut('slow');
      $.cookie('_sbav', 'value', { path: '/', expires: 5 });
      return false;
    });

    // wait 700 millaseconds after the page load to fade in
    $(window).bind("load", function() {
      // If the necessary elements are available on the page.
      if ($("#govdelivery-enabled").length) {
        // if the user has dismissed this dialog before don't bug them again for it until the cookie dies
        if ($.cookie('_sbav')) {
        } else {
          setTimeout(function() {
            $('#block-govdelivery_splash-govdelivery_splash_block').fadeIn('slow');
          }, 700);
        }
      }
      else {
        // Hide any elements that are not supported when the necessary elements are not available to make govdelivery work.
        $(".govdelivery-email-link-header").hide();
        $('#block-govdelivery_splash-govdelivery_splash_block').hide(); // Just in case the styles don't load.
      }
    });
    // On Ajax complete.
    $(document).ajaxComplete(function() {
      // On clicking the "No Thanks" button.
      $('#govdelivery-no-thanks-again').click(function(){
        $('#block-govdelivery_splash-govdelivery_splash_block').fadeOut('slow');
        return false;
      });

      // close the window, but do not set the cookie - when the close window div is clicked
      $('#govdelivery-close-window').click(function() {
        $.cookie('_sbav', 'value', { path: '/', expires: 3 });
        $('#block-govdelivery_splash-govdelivery_splash_block').fadeOut('slow');
      });

      $( ".govdeliver-steptwo #govdelivery-submit" ).mousedown(function() {
        $("#edit-select-all-topics").defaultChecked = false;
      });
      // On clicking "Submit" on step 2.
      //$(".govdeliver-steptwo #govdelivery-submit").live('click', function(){
      // .live() has been deprecated and replaced by .on()
      $(".govdeliver-steptwo ").on('click', "#govdelivery-submit", function(){
        // Store all the topic codes.
        var topicsCode = [];
        // For each selected subtopics
        $(".checkboxes-subtopics > div.checked input").each(function(index){
          // Push the "value" into an array.
          $(".checkboxes-subtopics").remove();
          topicsCode.push(this.value);
          this.checked = '';
          $('div.checkboxes-subtopics div.checked').removeClass('checked active').addClass('inactive');
          $('.checkboxes-subtopics').remove();
          // this.remove();
        });
        $(".form-type-checkbox > input").each(function(index){
          this.checked = '';
        });
        // Convert the Topics array to Json.
        var topicsCodeJson = JSON.stringify(topicsCode);
        // set hidden form field, id 'user_topic_values', value to json string
        $("#govdelivery-hidden-form-topics").attr("value", topicsCodeJson);
        $("#edit-select-all-topics").remove();
        return false;
      });

      // when step three is initiated, set the cookie that it was a successful form submission
      if (!$('#block-govdelivery_splash-govdelivery_splash_block .govdeliver-stepthree').length == 0) {
        $.cookie('_sbav', 'value', { path: '/', expires: 365 * 5 });
        // when step three is initiated, set the cookie that it was a successful form submission
        setTimeout(function() {
          $('#block-govdelivery_splash-govdelivery_splash_block').fadeOut('slow');
        }, 3000);
      }

      // this will add an extra box to show what the user is currently selecting for topics
      // for business.usa.gov we don't need show user what he/she selected
      //$('<div class="govdelivery-checkbox-list"></div>').insertAfter('#block-govdelivery_splash-govdelivery_splash_block .govdeliver-steptwo #edit-subtopics');

      // Set all topics to inactive upon loading step 2
      // we have changed this to stay active when loading
      //$("div.checkboxes-subtopics div.topic-checkbox").addClass('inactive');

      //$("#edit-subtopics > div >input").each(function(index){
      //  $(this).parent().addClass("inactive");
      //});
      
      // we have disabled select-all checkbox
      //$("#govdelivery-select-all").show();
      
      // When ever user checks a state box
      // We don't need state selection functinality
      /*
      $("div.checkboxes-states input[type='checkbox']").click(function() {
          var states;
          var subtopicKey;
          var regexMatch;
          var clickedStates;
          var selectedTopic;
          var selectedTopicClass;
          var selectedTopicClassFixed;

          // If the check box is checked.
          if ($(this).attr('checked') == true){
            // Get the label of the check box.
            states = $(this).next().text().trim();
            // For each subtopic.
            $("#edit-subtopics > div >input").each(function(index){
              // Get the value of the "value" attribute.
              subtopicKey = $(this).attr("value");
              // Create a regex to search for text after " : " in the "subtopicKey" for e.g.
              // For subtopicKey "USSBA_145|USSBA_C13:Alabama", return "Alabama".
              regexMatch = subtopicKey.match(/[a-zA-z]*$/);
              // If the value of regex matches value of "state".
              if (regexMatch[0] == states){
                // Show the subtopics.
                $(this).parent().removeClass("inactive").addClass("active");
                $(this).attr("checked", "checked");
                selectedTopic = $(this).next('label').text();
                selectedTopicClass = $(this).next('label').text().toLowerCase().replace(' ','');
                selectedTopicClassFixed = selectedTopicClass.replace(/([^A-Z^a-z^0-9])/g, "");
                // var existingClass = $(this).parents('.topic-checkbox').attr('class');
                // var existingClassFixed = existingClass.replace("topic-checkbox ", "").replace("active", "").replace("checked", "").replace("visible","");
              $('div.govdelivery-checkbox-list').append('<div class="'+ selectedTopicClassFixed +'">'+ selectedTopic +'</div>');
              }
            });
          }else{ // If the user box is un-checked.
            // Get the label of the check box.
            states = $(this).next().text().trim();
            // For each subtopic.
            $("#edit-subtopics > div >input").each(function(index){
              // Get the value of the "value" attribute.
              subtopicKey = $(this).attr("value");
              // Create a regex to search for text after " : " in the "subtopicKey" for e.g.
              // For subtopicKey "USSBA_145|USSBA_C13:Alabama", return "Alabama".
              regexMatch = subtopicKey.match(/[a-zA-z]*$/);
              // If the value of regex matches value of "state".
              if (regexMatch[0] == states){
                // Hide the subtopics.
                $(this).parent().removeClass("active").addClass("inactive");
                selectedTopic = $(this).next('label').text();
                selectedTopicClass = $(this).next('label').text().toLowerCase().replace(' ','');
                selectedTopicClassFixed = selectedTopicClass.replace(/([^A-Z^a-z^0-9])/g, "");
              }
              // For each value in step 3, "Your selected topic."
              $('div.govdelivery-checkbox-list > div').each(function(index){
                // If the class
                checkboxlistClass = $(this).attr("class");
                if (selectedTopicClassFixed == checkboxlistClass){
                  $(this).remove();
                }
              });
            });
          }

          var active = $('div.checkboxes-subtopics div.visible').length;
          var checked = $('div.checkboxes-subtopics div.checked').length;
          if (active == 0 && checked == 0) {
            // $("#govdelivery-select-all").hide();
            $("#govdelivery-select-all input[type='checkbox']").attr('checked', true);
          }
          else if (active === checked) {
            $("#govdelivery-select-all").show();
            $("#govdelivery-select-all input[type='checkbox']").attr('checked', true);
          }
          else {
            // $("#govdelivery-select-all input[type='checkbox']").attr('checked', false);
          }
      });
			*/
      // This deals with every time a user checks a topic.
      $("div.checkboxes-subtopics input[type='checkbox']").click(function() {
        // When you select a topic in step 2(Select Topics).
        if ($(this).is(':checked')) {
          // Make the check box "active" from "inactive".
          $(this).parents('div.topic-checkbox').removeClass('inactive').addClass('checked active');
          // Get the topic.
          var topic = $(this).next('label').text();
          // Convert this topic to a class.
          var topicClass = $(this).next('label').text().toLowerCase().replace(' ','');
          // Parse the class and replace un-necessary charaters.
          var topicClassFixed = topicClass.replace(/([^A-Z^a-z^0-9])/g, "");
          // Get the all the classes the topic's checkbox has.
          var exisitingClass = $(this).parent('.form-type-checkbox').attr('class');
          // Parse through all the classes and replace un-necessary charaters and classes.
          var existingClassFixed = exisitingClass.replace("form-type-checkbox ", "").replace("active", "").replace("checked", "").replace("visible","");
          // Append the topic to step 3(Your selected topics).
          $('div.govdelivery-checkbox-list').append('<div class="'+ existingClassFixed +' '+ topicClassFixed.toLowerCase() + '">' + topic + '</div>');
        }
        else { // When you un-check the check box.
          $('#edit-select-all-topics').attr('checked', false);
          var topic = $(this).next('label').text().toLowerCase().replace(' ','');
          var topicFixed = topic.replace(/([^A-Z^a-z^0-9])/g, "").toLowerCase();
          $(this).parents('div.topic-checkbox').removeClass('checked active').addClass('inactive');
          $('div.govdelivery-checkbox-list div.'+ topicFixed).remove();
        }
        var activeTopicsLen;
        var checkedTopicsLen;
        // Number of topics displayed.
        activeTopicsLen = $("#edit-subtopics .active > input").length;
        // Number of topics not displayed.
        checkedTopicsLen = $("#edit-subtopics .active > input:checked").length;
        // If all the checkboxes are checked.
        if (activeTopicsLen == checkedTopicsLen){
          // "Select All Topics" should be checked.
          $('#edit-select-all-topics').attr('checked', true);
        }else{
          // "Select All Topics" should be un-checked.
          $('#edit-select-all-topics').attr('checked', false);
        }
      });

      // when the user clicks to select all then all topics that are active in the topic box will get checked and added to the user selection
      // for now select-all functinality checkbox is hidden
      /*
      $('#govdelivery-select-all input[type="checkbox"]').click(function(){
        if ($("#edit-select-all-topics").is(":checked")){
            var selectedTopic;
            $("#edit-subtopics > div >input").each(function(index){
              if ($(this).parent().hasClass("active")){
                $(this).attr("checked", true);
                topic = $(this).parent().text().trim();
                // Convert this topic to a class.
                var topicClass = $(this).next('label').text().toLowerCase().replace(' ','');
                // Parse the class and replace un-necessary charaters.
                var topicClassFixed = topicClass.replace(/([^A-Z^a-z^0-9])/g, "");
                // Get the all the classes the topic's checkbox has.
                var exisitingClass = $(this).parent('.form-type-checkbox').attr('class');
                // Parse through all the classes and replace un-necessary charaters and classes.
                var existingClassFixed = exisitingClass.replace("form-type-checkbox ", "").replace("active", "").replace("checked", "").replace("visible","");
                $('div.govdelivery-checkbox-list').append('<div class="'+ existingClassFixed +' '+ topicClassFixed.toLowerCase() + '">'+ topic +'</div>');
              }
            });
          }else{
            $("#edit-subtopics > div >input").each(function(index){
              if ($(this).parent().hasClass("active")){
                $(this).attr("checked", false);
              }
            });
            $('div.govdelivery-checkbox-list > div').each(function(index){
              $(this).remove();
            });
          }
      });
			*/
      // continually keep track of user responses
      var numberCategories = $('div.checkboxes-states input[type="checkbox"]').length;
      for (var x = 0; x < numberCategories; ++ x) {
        var categoryText = $('div.checkboxes-states div:eq('+x+') label').text();
        $('div.checkboxes-states div:eq('+x+') input[type="checkbox"]').addClass(categoryText.toLowerCase().replace(/ /g,'')+'-category-container');
      }

      $("div.topic-checkbox input[type='checkbox']:checked").parents('.topic-checkbox').removeClass('inactive').addClass('visible checked active').show();
      var active = $('div.checkboxes-subtopics div.visible').length;
      var checked = $('div.checkboxes-subtopics div.checked').length;
      var numberItems = $('div.checkboxes-subtopics div.checked.active').length;
      for (var n = 0; n < numberItems; ++ n) {
        var categoryClass = $('div.checkboxes-subtopics div input[type="checkbox"]:checked:eq('+n+')').parents('.topic-checkbox').attr('class');
        var correctedClass = categoryClass.replace('topic-checkbox','').replace(' visible checked active','').replace(' ','');
        var thisText = $('div.checkboxes-subtopics div.checked.active:eq('+n+') label').text();
        var thisTopicClass = $('div.checkboxes-subtopics div.checked.active:eq('+n+') label').text().toLowerCase().replace(' ','');
        var thisTopicClassFixed = thisTopicClass.replace(/([^A-Z^a-z^0-9])/g, "");
        var thisTopicActualClass = $('div.checkboxes-subtopics div.checked.active:eq('+n+') label').parents('.topic-checkbox').attr('class');
        var toBeTopicTagged = thisTopicActualClass.replace("topic-checkbox ", "");
        $('div.govdelivery-checkbox-list').append('<div class="'+toBeTopicTagged+' '+thisTopicClassFixed.toLowerCase()+'">'+thisText+'</div>');
        $('.'+correctedClass+'-category-container').attr('checked', true);
      }

      // monitor all newly activated entries and show or hide the extra checkbox which automatically adds them to the user selection
      if (active == 0 && checked == 0) {
        // $("#govdelivery-select-all").hide();
        $("#govdelivery-select-all input[type='checkbox']").attr('checked', true);
      }
      else if (active === checked) {
        $("#govdelivery-select-all").show();
        $("#govdelivery-select-all input[type='checkbox']").attr('checked', true);
      }
      else {
        // $("#govdelivery-select-all input[type='checkbox']").attr('checked', false);
      }
    });
  });
})(jQuery);

