<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>


<!-- NOTE: The following div is rendered from <?php print basename(__FILE__); ?> -->
<div id="wizard-list-with-description" class="hidden-xs" rendersource="<?php print basename(__FILE__); ?>">
	<div class="list-group wizard-list">
		<?php foreach ($rows as $id => $row): ?>

			<?php if ($view->result[$id]->field_field_summary): ?>

				<a href="<?php print $view->result[$id]->field_field_swimlane_wizurl[0]['raw']['value'] ?>" class="list-group-item <?php print $classes_array[$id] ?>">
					<span class="list-group-item-heading"><?php print $view->result[$id]->node_title; ?></span>
					<p class="list-group-item-text"><?php print $view->result[$id]->field_field_summary[0]['raw']['value']; ?></p>
					<span class="launch-link">Launch the Wizard <span class="glyphicon glyphicon-play"></span></span>
				</a>

			<?php endif; ?>

		<?php endforeach; ?>
	</div>
</div>

<!-- NOTE: The following div is rendered from <?php print basename(__FILE__); ?> -->
<div id="wizard-list-all" class="hidden-xs" rendersource="<?php print basename(__FILE__); ?>">
	<div id="carousel1">
		<div class="slider">
			<div class="list-group wizard-list-all slide">
				<?php foreach ($rows as $id => $row): ?>
					<?php if (!$view->result[$id]->field_field_summary and $id <= 8): ?>
					  	<a href="<?php print $view->result[$id]->field_field_swimlane_wizurl[0]['raw']['value'] ?>" class="list-group-item slide <?php print $classes_array[$id] ?>">
							<?php print $view->result[$id]->node_title;?> 
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<div class="list-group wizard-list-all slide">
				<?php foreach ($rows as $id => $row): ?>
					<?php if (!$view->result[$id]->field_field_summary and $id > 8): ?>
					  	<a href="<?php print $view->result[$id]->field_field_swimlane_wizurl[0]['raw']['value'] ?>" class="list-group-item slide <?php print $classes_array[$id] ?>">
							<?php print $view->result[$id]->node_title; ?>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<!-- NOTE: The following div is rendered from <?php print basename(__FILE__); ?> -->
<div id="wizard-list-all" class="visible-xs-block" rendersource="<?php print basename(__FILE__); ?>">
	<br>
	<div class="list-group wizard-list-all">
		<?php foreach ($rows as $id => $row): ?>
		  	<a href="<?php print $view->result[$id]->field_field_swimlane_wizurl[0]['raw']['value'] ?>" class="list-group-item slide <?php print $classes_array[$id] ?>">
				<?php print $view->result[$id]->node_title; ?>
			</a>
		<?php endforeach; ?>
	</div>
</div>
