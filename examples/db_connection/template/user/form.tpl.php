<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<h2><?php echo $title ?></h2>
<form method="post">
  User ID:<br/>
  <i><?php echo $id ?></i><br/>
  <br/>
  Name:<br/>
  <input type="text" name="name" value="<?php echo $name ?>" /><br/>
  <br/>
  <input type="submit" name="submit" value="Save" />
</form>
