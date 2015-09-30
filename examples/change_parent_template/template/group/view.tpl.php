<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<h2><?php echo $title ?></h2>
<p><a href="?module=group&action=add">Add</a></p>
<table border="1">
  <tr><th>Group ID</th><th>Group Name</th></tr>
  <?php foreach($group_list as $group): ?>
    <tr><td><?php echo $group["id"] ?></td><td><?php echo $group["name"] ?></td></tr>
  <?php endforeach; ?>
</table>
