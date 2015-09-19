<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<h2><?php echo $title ?></h2>
<form method="post">
  User ID:<br/>
  <input type="text" name="name" /><br/>
  Realname:<br/>
  <input type="text" name="realname" /><br/>
  <br/>
  <input type="submit" name="submit" value="Save" />
</form>
