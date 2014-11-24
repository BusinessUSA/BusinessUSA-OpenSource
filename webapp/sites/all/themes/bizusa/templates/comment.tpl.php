<div class="<?php print $classes . ' ' . $zebra; ?>">
  <?php print render($title_prefix); ?>
    <h3 class="title"><?php print $title ?></h3>
  <?php print render($title_suffix); ?>

  <?php print $picture ?>
  <span class="submitted"><?php print $created; ?> — <?php print $author; ?></span>
  <?php hide($content['links']); ?>

  <?php print render($content); ?>

  <?php if ($signature): ?>
    <div class="signature"><?php print $signature ?></div>
  <?php endif; ?>

  <?php if (!empty($content['links'])): ?>
    <div class="links"><?php print render($content['links']); ?></div>
  <?php endif; ?>

</div>
