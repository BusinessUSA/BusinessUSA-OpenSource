<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html">
    <?php print render($title_prefix); ?>
    <?php
        //dsm( $node );

    ?>


    <div class="blog-image">

    </div>
    <div class="blog-content-container">
        <div class="blog-content-title">
			<?php print $node->title; ?>
        </div>
		<div>
			<?php print $node->field_blog_submit_dt['und'][0]['value'];?>
		</div>
		<div>
			<?php print $node->field_blog_text['und'][0]['safe_value']; ?>
		</div>
    </div>

		<?php if (!empty($content['last_date_modified'])): ?>
				<?php print render($content['last_date_modified']); ?>
		<?php endif; ?>		

</div>

    <script>
        $(document).ready(function(){

            if ($('#block-views-blog-block_1').length > 0 )
            {
                $('#block-views-blog-block_1').find('.view-content').toggle();

                $('#edit-field-blog-submit-dt-value-value').toggle();
                $('.form-item-field-blog-category-value').toggle();
            }

            $('.title h1').text('Blog');
        });

        $('#block-views-blog-block_1').click(function(){
            $('#block-views-blog-block_1').find('.view-content').toggle();
        });
        $('#edit-field-blog-submit-dt-value-wrapper').click(function(){
            $('#edit-field-blog-submit-dt-value-value').toggle();
        });
        $('#edit-field-blog-category-value-wrapper').click(function(){
            $('.form-item-field-blog-category-value').toggle();
        });

    </script>
