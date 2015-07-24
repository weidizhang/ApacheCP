<?php
/*
 * ApacheCP
 * @author Weidi Zhang http://github.com/ebildude123
 * @license CC BY-NC-SA 4.0 (See LICENSE file)
 */

require "inc.admin.php";
require "inc.session.php";
if (isLoggedIn()) {
	redirectToHome();
}

$errMsg = "";

if (isset($_POST["user"], $_POST["pass"])) {
	$user = trim($_POST["user"]);
	$pass = trim($_POST["pass"]);
	
	if (empty($user) || empty($pass)) {
		$errMsg = "Please fill out all the fields.";
	}
	elseif ((strtolower($user) != strtolower($username)) || (hash("whirlpool", $pass) != $password)) {
		$errMsg = "Invalid username or password.";
	}
	else {
		$_SESSION["admin"] = 1;
		redirectToHome();
	}
}

$pageName = "Login";
include "header.php";

if (!empty($errMsg)) {
?>
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-danger"><?php echo $errMsg; ?></div>
	</div>
</div>
<?php
}
?>
<div class="row">
	<div class="col-lg-12">
		<form method="POST" action="./login.php">
			<label>Username:</label>
			<input type="text" class="form-control" placeholder="Administrator" name="user">
			<br>
			<label>Password:</label>
			<input type="password" class="form-control" placeholder="*********" name="pass">
			<br>
			<div class="pull-right">
				<button type="submit" class="btn btn-info">
					<span class="glyphicon glyphicon-user"></span> Login
				</button>
			</div>
		</form>
	</div>
</div>
<?php
include "footer.php";
?>