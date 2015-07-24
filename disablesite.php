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

$pageName = "Disable Site";
include "header.php";

$errMsg = "";

if (isset($_GET["name"])) {
	$siteName = trim($_GET["name"]);
	$disableCmd = shell_exec("./shell/apachecphelper a2dissite " . $siteName . " 2>&1");
	
	if (strpos($disableCmd, "already disabled") !== false) {
		$errMsg = "Site is already disabled.";
	}
	elseif (strpos($disableCmd, "does not exist") !== false) {
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
		<div class="alert alert-success">Site has successfully been disabled.</div>
	</div>
</div>
<?php
}

include "footer.php";
?>