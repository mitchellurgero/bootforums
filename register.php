<?php
session_start();
require("db.php");
require("config.php");
require("simple-php-captcha.php");

$_SESSION['captcha'] = simple_php_captcha( $config['captcha']);
//Begin page
include("header.php");
echo '<div class="container">';
//Body content
?>
<div class="page-header">
  <h1>Registration</h1>
</div>
<p><?php if($_GET['msg']){ echo $_GET['msg']; } ?></p>
<form action="submit.php" method="POST">
	<input type="hidden" name="type" id="type" value="reg" />
	<div class="form-group">
    <label for="user">Username:</label>
    <input type="username" class="form-control" id="user" name="user">
  </div>
  <div class="form-group">
    <label for="pass">Password:</label>
    <input type="password" class="form-control" id="pass" name="pass">
  </div>
  <div class="form-group">
  	<label for="cap">Captcha Request:</label><br />
  	<img src="<?php echo $_SESSION['captcha']['image_src']; ?>"><input type="text" name="cap" id="cap" rows="8">
  </div>
  <button type="submit" class="btn btn-primary pull-right">Submit</button>
  
</form>
<?php
echo '</div>';
include("footer.php");
?>