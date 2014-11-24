<div class="quickfactlistings-mastercontainer">

    <?php
        $viewHtml = views_embed_view('quick_facts', 'all_quick_facts');
        if ( is_null($viewHtml) ) {
            print '<b>Error: This environment does not have the Blogs View. Please <a href="https://qa.business.usa.reisys.com/admin/structure/views/view/quick_facts/export">export this View from QA by clicking here</a>, and then <a href="/admin/structure/views/import">import the View into this environment here</a>.</b> ';
        } else {
            print $viewHtml;
        }
    ?>
    
</div>