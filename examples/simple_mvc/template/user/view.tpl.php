<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<h2><?php echo $title ?></h2>
<p><a href="?module=user&action=add">Add</a></p>
<table cellspacing="0" class="table collapse" width="500">
  <tr class="header"><th>User ID</th><th>User Name</th></tr>
  <?php foreach($user_list as $user): ?>
    <tr class="row"><td><?php echo $user["id"] ?></td><td><?php echo $user["name"] ?></td></tr>
  <?php endforeach; ?>
</table>
