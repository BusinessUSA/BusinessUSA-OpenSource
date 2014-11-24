<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>">
  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php print $user_picture; ?>

  <?php if ($display_submitted): ?>
    <span class="submitted"><?php print $date; ?> — <?php print $name; ?></span>
  <?php endif; ?>

  <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    hide($content['field_learn_more']);
    print render($content);
  ?>

  <?php if (!empty($content['links']['terms'])): ?>
    <?php print render($content['links']['terms']); ?>
  <?php endif;?>

  <?php if (!empty($content['links'])): ?>
    <?php print render($content['links']); ?>
  <?php endif; ?>

    <?php if ( !empty($node->field_learn_more['und'][0]['value']) ): ?>
        <div>
            <label class="lblclass">Learn More: </label>
            <?php print '<a href="'. $node->field_learn_more['und'][0]['value'] .'" target="_blank">' . $node->field_learn_more['und'][0]['value'] . '</a>'; ?>
        </div>
    <?php endif; ?>


  <?php if (!empty($content['last_date_modified'])): ?>
      <?php print render($content['last_date_modified']); ?>
  <?php endif; ?>   
</div> <!-- /node-->

<?php print render($content['comments']); ?>
