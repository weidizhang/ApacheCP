<?php
/*
 * ApacheCP
 * @author Weidi Zhang http://github.com/ebildude123
 * @license CC BY-NC-SA 4.0 (See LICENSE file)
 */

require "inc.session.php";
if (!isLoggedIn()) {
	redirectToLogin();
}

$pageName = "Enable Site";
include "header.php";

$errMsg = "";

if (isset($_GET["name"])) {
	$siteName = trim($_GET["name"]);
	$enableCmd = shell_exec("./shell/apachecphelper a2ensite " . $siteName . " 2>&1");
	
	if (strpos($enableCmd, "already enabled") !== false) {
		$errMsg = "Site is already enabled.";
	}
	elseif (strpos($enableCmd, "does not exist") !== false) {
		$errMsg = "Site is non-existent.";
	}
	else {
		shell_exec("./shell/apachecphelper service apache2 reload");
	}
}
else {
	$errMsg = "Missing parameter: site not sent in request.";
}

if (!empty($errMsg)) {
?>
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-danger"><?php echo $errMsg; ?></div>
	</div>
</div>
<?php
}
else {
?>
<div class="row" id="addSite">
	<div class="col-lg-12">
		<div class="alert alert-success">Site has successfully been enabled.</div>
	</div>
</div>
<?php
}

include "footer.php";
?>