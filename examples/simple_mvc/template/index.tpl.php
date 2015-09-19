<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $page_title ?></title>
</head>
<body>
  <ul>
    <?php foreach($menu as $title=>$link): ?>
      <li><a href="<?php echo $link ?>"><?php echo $title ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php echo $content ?>
</body>
</html>
