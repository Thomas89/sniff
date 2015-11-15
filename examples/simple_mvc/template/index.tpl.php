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
  <ul id="menu">
    <?php foreach($menu as $title=>$link): ?>
      <li><a href="<?php echo $link ?>"><?php echo $title ?></a></li>
    <?php endforeach; ?>
  </ul>
  <div id="breadcrumb"><?php echo $breadcrumb ?></div>
  <div id="content">
    <?php echo $content ?>
  </div>
</body>
</html>
