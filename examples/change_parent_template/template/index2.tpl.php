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
  <div id="sidebar">
    <p class="title"><b>Menu</b></p>
    <ul class="menu">
      <?php foreach($menu as $title=>$link): ?>
        <li><a href="<?php echo $link ?>"><?php echo $title ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div id="breadcrumb"><?php echo $breadcrumb ?></div>
  <div id="content">
    <p><i>The second template using sidebar menu.</i></p>
    <?php echo $content ?>
  </div>
</body>
</html>
