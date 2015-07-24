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

$pageName = "Sites";
include "header.php";

$sitesAvailableDir = glob("/etc/apache2/sites-available/*.conf");
$sitesEnabledDir = glob("/etc/apache2/sites-enabled/*.conf");

$sites = array();

foreach ($sitesAvailableDir as $site) {
	$enabled = false;
	$site = basename($site, ".conf");
	
	if (in_array("/etc/apache2/sites-enabled/" . $site . ".conf", $sitesEnabledDir)) {
		$enabled = true;
	}
	
	$sites[] = array(
		"name" => $site,
		"enabled" => $enabled
	);
}
?>
<script>
$(document).ready(function() {
	$(".btn-danger").click(function() {
		var shouldDelete = confirm("Are you sure you want to delete this site?");
		if (shouldDelete != true) {
			return false;
		}
	});
});
</script>

<div class="row" id="addSite">
	<div class="col-lg-12">
		<div class="pull-right">
			<a href="addsite.php" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Add a site</a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Site</th>
						<th>Enabled</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?php
foreach ($sites as $site) {
?>
	<tr>
		<td><?php echo $site["name"]; ?></td>
		<td><span class="glyphicon glyphicon-<?php echo ($site["enabled"]) ? "ok" : "remove"; ?>"></span></td>
		<td class="center">
<?php
if ($site["enabled"]) {
	echo "<a href=\"disablesite.php?name=" . $site["name"] . "\" class=\"btn btn-warning btn-sm\">Disable</a>";
}
else {
	echo "<a href=\"enablesite.php?name=" . $site["name"] . "\" class=\"btn btn-success btn-sm\">Enable</a>";
}
?>
			<a href="deletesite.php?name=<?php echo $site["name"]; ?>" class="btn btn-danger btn-sm">Delete</a>
		</td>
	</tr>
<?php
}
?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
include "footer.php";
?>