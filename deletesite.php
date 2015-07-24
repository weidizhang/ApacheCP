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

$pageName = "Delete Site";
include "header.php";

$errMsg = "";

if (isset($_GET["name"])) {
	$siteName = trim($_GET["name"]);
	
	$configFile = "/etc/apache2/sites-available/" . $siteName . ".conf";
	if (file_exists($configFile)) {
		shell_exec("./shell/apachecphelper a2dissite " . $siteName);
		shell_exec("./shell/apachecphelper service apache2 reload");
		shell_exec("./shell/apachecphelper rm " . $configFile);
	}
	else {
		$errMsg = "Site is non-existent.";
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
		<div class="alert alert-success">Site has successfully been deleted.</div>
	</div>
</div>
<?php
}

include "footer.php";
?>