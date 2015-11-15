<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<h2><?php echo $title ?></h2>
<p><a href="?module=group&action=add">Add</a></p>
<table cellspacing="0" class="table collapse" width="500">
  <tr class="header"><th>Group ID</th><th>Group Name</th></tr>
  <?php foreach($group_list as $group): ?>
    <tr class="row"><td><?php echo $group["id"] ?></td><td><?php echo $group["name"] ?></td></tr>
  <?php endforeach; ?>
</table>
