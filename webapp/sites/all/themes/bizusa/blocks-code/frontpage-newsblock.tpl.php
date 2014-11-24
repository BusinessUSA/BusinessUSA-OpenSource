<?php
    /*
        Despite this files extension, it is involved from within a block. 
        This file contains the HTML/PHP code for the "front_page_news_block" block
    */
?>

<div class="row" style="margin-top:8px;">

  <?php print views_embed_view('wizard_list', 'block'); ?>

  <div class="visible-xs-block">
    
    <a href="/request-appointment-and-closest-resource-centers" class="btn btn-secondary btn-lg btn-block">Locate a Resource Center</a><br>
    
    <div class="panel panel-carousel">
      <div class="panel-heading">
        <h3 class="panel-title"><a href="/business-news">Business News</a></h3>
      </div>
      <div class="panel-body">
        <?php print views_embed_view('front_page_news_views', 'business_news'); ?>
      </div>
    </div>

  </div>

  <div id="news-section-carousel" class="hidden-xs">
    <div id="carousel2" rendersource="frontpage-newsblock.tpl.php" xmlns="http://www.w3.org/1999/html">
        <div class="slider">
            <div class="panel panel-carousel slide">
              <div class="panel-heading">
                <h3 class="panel-title">Featured Item</h3>
              </div>
              <?php print views_embed_view('front_page_news_views', 'featured_item'); ?>
            </div>

            <div class="panel panel-carousel slide">
              <div class="panel-heading">
                <h3 class="panel-title"><a href="/business-news">Business News</a></h3>
              </div>
              <?php print views_embed_view('front_page_news_views', 'business_news'); ?>
            </div>

            <div class="panel panel-carousel slide">
              <div class="panel-heading">
                <h3 class="panel-title"><a href="/whats-new">What&#39;s New</a></h3>
              </div>
              <?php print views_embed_view('front_page_news_views', 'whats_new'); ?>
            </div>

            <div class="panel panel-carousel slide">
              <div class="panel-heading">
                <h3 class="panel-title"><a href="/quick-facts">Quick Facts</a></h3>
              </div>
              <?php print views_embed_view('front_page_news_views', 'quick_facts'); ?>
            </div>
        </div>
    </div>
  </div>

</div>