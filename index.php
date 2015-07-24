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

$pageName = "Dashboard";
include "header.php";

$apacheInfo = explode("\n", shell_exec("apache2 -v"));
$apVersion = substr($apacheInfo[0], strpos($apacheInfo[0], ":") + 2);
$apBuildDate = substr($apacheInfo[1], strpos($apacheInfo[1], ":") + 2);

$getStatus = shell_exec("service apache2 status");
if (strpos($getStatus, "is running") !== false) {
	$apStatus = "Running";
}
else {
	$apStatus = "Stopped";
}
?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Server Information
			</div>
			<div class="panel-body">
				<p>
					<label>Apache Version:</label> <?php echo $apVersion; ?>
					<br>
					<label>Build Date:</label> <?php echo $apBuildDate; ?>
					<br>
					<label>Status:</label> <?php echo $apStatus; ?>
				</p>
			</div>
		</div>
	</div>
</div>
<?php
include "footer.php";
?>