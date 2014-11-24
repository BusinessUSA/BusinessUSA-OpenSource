<?php
//  $Id$
/*
  This template file is used to display the popup of Shell.  Most of the extraneous styles and
  other content (like logo, sitename, etc) have been removed.  The point of this is to give
  Shell as much room as possible in the popup, and also to ensure that the javascript
  resizing functions are more likely to work accurately.
  
  You can override this file!  Just copy it into your theme directory and make whatever
  changes you wish to it.
*/
?>
<?php print render($page['header']); ?>

<div id="shell-popup-page">

      <div id="content-area">
          <?php print render($page["content"]); ?>
        </div>

</div>
