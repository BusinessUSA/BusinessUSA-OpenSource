<!-- NOTE: The following UL is rendered from <?php print basename(__FILE__); ?> -->
<ul class="list-group default-block-list">
	<?php foreach ($rows as $id => $row): ?>

	    <li <?php print ' class="list-group-item ' . $classes_array[$id] .'"';  ?> ><?php print $row; ?></li>
	  
	<?php endforeach; ?>
</ul>