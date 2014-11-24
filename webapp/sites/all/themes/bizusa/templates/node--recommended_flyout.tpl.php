<div class="preview-recommended-flyout-container">
  <div class="preview-recommended-flyout-wrap" style="width: 360px;">
    <div class="preview-recommended-flyout">
      <a href="<?php print $node->field_landing_page_path[LANGUAGE_NONE][0]['value']; ?>" class="preview-recommended-flyout-link" target="_blank">
        <?php if(!empty($node->field_image[LANGUAGE_NONE][0]['uri'])): ?>
        <img src="<?php print file_create_url($node->field_image[LANGUAGE_NONE][0]['uri']) ?>" class="preview-recommended-flyout-content-image" width="70" height="70">
        <?php endif; ?>
        <h6 class="preview-recommended-flyout-heading"><?php print $node->title ?></h6>
        <h4 class="preview-recommended-flyout-snippet"><?php print (!empty($node->field_snippet[LANGUAGE_NONE][0]['value'])) ? $node->field_snippet[LANGUAGE_NONE][0]['value'] : '' ?></h4>
      </a>
     <span class="preview-recommended-flyout-close">&#10006;</span>
     </div>
  </div>
  
  <?php if (!empty($content['last_date_modified'])): ?>
      <?php print render($content['last_date_modified']); ?>
  <?php endif; ?>
</div>