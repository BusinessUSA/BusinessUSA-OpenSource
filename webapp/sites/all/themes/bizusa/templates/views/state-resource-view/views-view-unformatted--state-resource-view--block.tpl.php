
<!-- NOTE: The following is rendered from <?php print basename(__FILE__); ?> -->
<?php foreach ($rows as $id => $row): ?>
  <div<?php if ($classes_array[$id]) { print ' class="list-group-item list-group-item-simple ' . $classes_array[$id] .'"';  } ?>>
    <?php print $row; ?>
  </div>
<?php endforeach; ?>