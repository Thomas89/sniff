<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<h2><?php echo $title ?></h2>
<?php if ($message != "") : ?>
  <p><b><?php echo $message ?></b></p>
<?php endif; ?>
<form method="post">
  User ID:<br/>
  <input type="text" name="user_id" /><br/>
  Realname:<br/>
  <input type="text" name="user_name" /><br/>
  <br/>
  <input type="submit" name="submit" value="Save" />
</form>
