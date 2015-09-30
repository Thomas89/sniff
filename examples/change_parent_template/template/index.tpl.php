<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $page_title ?></title>
  <style>
  a {
    color:#369;
  }

  ul.menu {
    padding: 0;
    margin: 0;
  }

  ul.menu li {
    display: inline;
    padding-right: 10px;
  }
  </style>
</head>
<body>
  <p>The first template using menu on the top.</p>
  <ul class="menu">
    <?php foreach($menu as $title=>$link): ?>
      <li><a href="<?php echo $link ?>"><?php echo $title ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php echo $content ?>
</body>
</html>
