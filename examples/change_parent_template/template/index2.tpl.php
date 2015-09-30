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

  #sidebar {
    float: left;
    width: 25%;
  }

  #content {
    float: left;
    width: 75%;
  }
  </style>
</head>
<body>
  <p>The second template using sidebar menu.</p>
  <div id="sidebar">
    <p><b>Menu</b></p>
    <ul class="menu">
      <?php foreach($menu as $title=>$link): ?>
        <li><a href="<?php echo $link ?>"><?php echo $title ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div id="content">
    <?php echo $content ?>
  </div>
</body>
</html>
