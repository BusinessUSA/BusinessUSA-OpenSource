<?php $current_display = $view->current_display; ?>

<?php if ($current_display == 'featured_item'): ?>
	<div class="list-group" rendersource="<?php print basename(__FILE__); ?>">
		<a href="<?php print $view->result[0]->field_field_featured_link[0]['raw']['url']; ?>">
			<img class="center-block" src="/sites/default/files/<?php print $view->result[0]->field_field_featured_image[0]['raw']['filename']; ?>">
		</a>
	</div>
<?php else: ?>

    <!-- NOTE: The following div is rendered from <?php print basename(__FILE__); ?> -->
	<div class="list-group" rendersource="<?php print basename(__FILE__); ?>">
		<?php foreach ($rows as $id => $row): ?>

			<?php
			switch ($current_display) {
			  case "business_news":
			    $itemLink = $view->result[$id]->field_field_news_link[0]['raw']['url'];
                if ( !empty($view->result[$id]->node_title) ) {
                    $itemTitle = $view->result[$id]->node_title;
                } else {
                    $itemTitle = $view->result[$id]->field_field_news_link[0]['raw']['title'];
                }
				$itemDescription = $view->result[$id]->field_body[0]['raw']['value'];
			    break;
			  case "whats_new":
			    $itemLink = $view->result[$id]->field_field_whats_new_link[0]['raw']['url'];
                if ( !empty($view->result[$id]->node_title) ) {
                    $itemTitle = $view->result[$id]->node_title;
                } else {
                    $itemTitle = $view->result[$id]->field_field_whats_new_link[0]['raw']['title'];
                }
				$itemDescription = $view->result[$id]->field_body[0]['raw']['value'];
			    break;
			  case "quick_facts":
			    $itemLink = $view->result[$id]->_field_data['nid']['entity']->field_learn_more['und'][0]['value'];
				$itemTitle = $view->result[$id]->node_title;
				$itemDescription = $view->result[$id]->field_field_qf_desc[0]['raw']['value'];
			    break;
			  default:
			    echo "Error";
			}
			?>

			<?php if ($itemDescription): ?>

				<a href="<?php print $itemLink  ?>" class="list-group-item list-group-item-carousel">
					<h4 class="list-group-item-heading"><?php print $itemTitle ?></h4>
					<p class="list-group-item-text"><?php print $itemDescription ?></p>
				</a>

			<?php else: ?>

				<a href="<?php print $itemLink ?>" class="list-group-item list-group-item-carousel">
					<?php print $itemTitle ?>
				</a>

			<?php endif; ?>

		<?php endforeach; ?>
	</div>


<?php endif; ?>



