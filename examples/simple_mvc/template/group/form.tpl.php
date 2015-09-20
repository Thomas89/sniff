<?php
// Prevent direct access
defined("INDEX") or die("");
?>
<h2><?php echo $title ?></h2>
<?php if ($message != "") : ?>
  <p><b><?php echo $message ?></b></p>
<?php endif; ?>
<form method="post">
  Group ID:<br/>
  <input type="text" name="group_id" /><br/>
  Group name:<br/>
  <input type="text" name="group_name" /><br/>
  <br/>
  <input type="submit" name="submit" value="Save" />
</form>
