<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<h2><?php echo $title ?></h2>
<p><a href="?module=user&action=add">Add</a></p>
<table cellspacing="0" class="table collapse" width="500">
  <tr class="header"><th>User ID</th><th>User Name</th><th colspan="2">&nbsp;</th></tr>
  <?php foreach($user_list as $user): ?>
    <tr class="row">
      <td><?php echo $user["id"] ?></td>
      <td><?php echo $user["name"] ?></td>
      <td><a href="index.php?module=user&action=update&id=<?php echo $user["id"] ?>">update</a></td>
      <td><a href="index.php?module=user&action=delete&id=<?php echo $user["id"] ?>">delete</a></td>
    </tr>
  <?php endforeach; ?>
</table>
