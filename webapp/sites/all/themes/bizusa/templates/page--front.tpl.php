<div id="navigation" style="display:none;">
  <div class="wrapper">
      <div id="__ba_panel"></div>
      <script type="text/javascript">
          var servername = window.location.origin;

          if (!servername) {
              servername = window.location.protocol +
                  "//" + window.location.hostname;
          }
          var _baLocale = "us",
              _baUseCookies = true,
              _baMode = servername + "/sites/all/themes/bizusa/images/browsealoud.png",
              _baHiddenMode = false, _baHideOnLoad = false;
          jQuery.getScript('http://www.browsealoud.com/plus/scripts/ba.js')
              .done(function() {
                  $(".browsealoud a").remove();
                  $(".browsealoud").append($("#__ba_panel"));
                  $("#__ba_panel").show();
                  $(".browsealoud").show();
              })
              .fail(function() {});
      </script>
      <?php print render($page['navigation']) ?>
  </div>
</div>

<div class="helpArea">
  <ul id="helpButton">
    <li>
      <a href="#" class="helpToggle" >ssshelp</a>
      <ul>
        <li><a href="javascript: alert('You can edit this help-link from page.tpl.php on line <?php print __LINE__; ?>'); void(0);" class="chat">Help Link 1</a></li>
        <li><a href="javascript: alert('You can edit this help-link from page.tpl.php on line <?php print __LINE__; ?>'); void(0);" class="ask">Help Link 2</a></li>
        <li><a href="javascript: alert('You can edit this help-link from page.tpl.php on line <?php print __LINE__; ?>'); void(0);" class="feedback">Help Link 3</a></li>
        <li><a href="javascript: alert('You can edit this help-link from page.tpl.php on line <?php print __LINE__; ?>'); void(0);" class="info">Help Link 4</a></li>
        <li><a href="javascript: alert('You can edit this help-link from page.tpl.php on line <?php print __LINE__; ?>'); void(0);" class="browse">Help Link 5</a></li>
      </ul>
    </li>
  </ul>
</div>
<div class="wrapper">

  <?php /* if ( function_exists('randomDevNote') ) { print randomDevNote(); } */ ?>

  <div id="header" class="row">
    
    <div class="col-xs-9 col-sm-6 col-md-5">

      <div class="row">
        
        <div class="col-sm-7 col-md-7 col-lg-6">
          <a class="text-hide businessusa-logo" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home">
            <?php print $site_name; ?>
          </a>
        </div>
        
        <div id="state-and-export-header" class="col-sm-5 col-md-5 col-lg-6">

          <?php if ( !empty($_GET['state']) ): ?>
            <a class="text-hide state-logo state-logo-<?php print $_GET['state']; ?> "  style="background: transparent url('/sites/all/themes/bizusa/images/microsite/state-logos/statelogo-<?php print strtoupper($_GET['state']);?>.png') no-repeat left top;">
              <?php print $fullstatename = acronymToStateName($_GET['state']);?>
            </a>
          <?php else: ?>
            <a class="text-hide export-logo" href="/export" title="Link to the Exporter Dasbhoard">Export Dashboard</a>
          <?php endif; ?>

        </div>
      
      </div>
    </div>

    <div class="visible-xs-block col-xs-3">
      <a href="#" class="text-hide toggleMobileMenu pull-right" id="toggle-mobile-menu">Show Mobile Menu</a>
    </div>

    <div class="visible-xs-block col-xs-12">
      <?php print theme('sharewidget', array()); ?>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-7">
      <div class="searchWrapper">
        <form class="sitewide-logo-and-search" rendersource="page.tpl.php" action="javascript: document.location = '/search/site/' + jQuery('#sitewide-search-input').val();">
          <div class="input-group">
            <label class="element-invisible" for="sitewide-search-input">Search</label>
            <input class="form-control input-lg" autocomplete="off" type="text" id="sitewide-search-input" placeholder="Start Searching Here" />
            <span class="input-group-btn">
              <input class="btn btn-primary btn-lg" type="submit" id="sitewide-search-button" value="Search" />
            </span>
          </div>
        </form>
        <div class="busa-autocomplete-responce-container"></div>
      </div>
    </div>
  
  </div>


  <?php if ($site_slogan): ?>
    <h2 id="slogan"><?php print $site_slogan; ?></h2>
  <?php endif; ?>

  <?php if ($page['header']): ?>
    <?php print render($page['header']); ?>
  <?php endif; ?>

  <div class="row">
    <div class="col-xs-12">
      <?php print $breadcrumb; ?>
    </div>
  </div>

  <?php if ($page['highlight']): ?>
    <?php print render($page['highlight']) ?>
  <?php endif; ?>
  
  <div id="contentArea">
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>

      <?php print $messages; ?>
      
      <?php print render($page['help']); ?>

      <?php if ($tabs): ?>
        <?php print render($tabs); ?>
      <?php endif; ?>

      <?php if ($action_links): ?>
        <ul><?php print render($action_links); ?></ul>
      <?php endif; ?>
      
      <div class="myusa-loading-msg" style="display: none; overflow: hidden; clear: both; padding: 25px 15px; background-color: white;" rendersource="<?php print basename(__FILE__); ?>">
          <img style="height: 35px; float: left" alt="Loading Image" src="/sites/all/themes/bizusa/images/bx_loader.gif" />
          <div style="line-height: 35px; padding-left: 10px; float: left;">
              Please wait as we load your personal dashboard...
          </div>
      </div>

      <?php print render($page['frontcontent']) ?>
  </div>
  <?php //print $feed_icons; ?>

  <?php if ($page['sidebar_first']): ?>
    <?php print render($page['sidebar_first']); ?>
  <?php endif; ?> <!-- /sidebar-first -->

  <?php if ($page['sidebar_second']): ?>
    <?php print render($page['sidebar_second']); ?>
  <?php endif; ?> <!-- /sidebar-second -->

  <?php if ($page['footer']): ?>
    <?php print render($page['footer']); ?>
  <?php endif; ?>
</div>

<script type="text/javascript" src="/sites/all/themes/bizusa/scripts/pages/front.js"></script>
    <script>
        $( document ).ready(function() {
            // Handler for .ready() called.
        });
        if (('#sitewide-search-input').length > 0)
        {
            $('#sitewide-search-input').focus(function ()
            {
                $(this).attr('placeholder','');
            });

            $('#sitewide-search-input').focusout(function()
            {
                $(this).attr('placeholder','Search BusinessUSA.Gov');
            });
        }
        if(jQuery(window).width() < 768){
            setTimeout(function() {
                $('#_ba__link').html("Browse Aloud");
                $('#_ba__link').show();
            }, 10000);
        }
    </script>
