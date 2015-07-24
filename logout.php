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

$pageName = "Logout";
include "header.php";

$_SESSION = array();
session_destroy();
?>
<div class="row">
	<div class="col-lg-12">
		You have been logged out.
	</div>
</div>
<?php
include "footer.php";
?>