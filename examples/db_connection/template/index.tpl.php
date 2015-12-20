<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $page_title ?></title>
  <?php echo $css ?>
</head>
<body>
  <div id="content">
    <?php echo $content ?>
  </div>
</body>
</html>
