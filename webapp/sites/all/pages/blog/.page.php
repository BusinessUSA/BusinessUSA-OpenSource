<div class="row">
    <div class="bloglistings-mastercontainer col-sm-12">
        <?php

            $viewHtml = views_embed_view('blog', 'page');
            if ( is_null($viewHtml) ) {
                print '<b>Error: This environment does not have the Blogs View. Please <a href="https://qa.business.usa.reisys.com/admin/structure/views/view/blog/export">export this View from QA by clicking here</a>, and then <a href="/admin/structure/views/import">import the View into this environment here</a>.</b> ';
            } else {
                print $viewHtml;
            }
        ?>
    </div>
</div>

