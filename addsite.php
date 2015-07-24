<?php
/*
 * ApacheCP
 * @author Weidi Zhang http://github.com/ebildude123
 * @license CC BY-NC-SA 4.0 (See LICENSE file)
 */

function is_valid_domain_name($domain_name)
{
	return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name)
		&& preg_match("/^.{1,253}$/", $domain_name)
		&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name));
}

function removeHTTP($domain) {
	if (substr($domain, 0, 7) == "http://") {
		$domain = substr($domain, 7);
	}
	elseif (substr($domain, 0, 8) == "https://") {
		$domain = substr($domain, 8);
	}
	return $domain;
}

require "inc.session.php";
if (!isLoggedIn()) {
	redirectToLogin();
}

$pageName = "Add Site";
include "header.php";

$showForm = true;
$errMsg = "";

if (isset($_POST["domain"], $_POST["admin"], $_POST["directory"], $_POST["override"], $_POST["forcewww"], $_POST["aliases"])) {
	$domain = strtolower(trim($_POST["domain"]));
	$email = trim($_POST["admin"]);
	$directory = trim($_POST["directory"]);
	$override = trim($_POST["override"]);
	$aliases = trim($_POST["aliases"]);
	$forcewww = trim($_POST["forcewww"]);
	
	$domain = removeHTTP($domain);
	
	if (substr($domain, 0, 4) == "www.") {
		$domain = substr($domain, 4);
	}
	
	if (empty($domain) || empty($email) || empty($directory) || empty($override) || empty($forcewww)) {
		$errMsg = "Please fill out all the required fields.";
	}
	elseif (($override != "All") && ($override != "None")) {
		$errMsg = "Invalid allow override option selected.";
	}
	elseif (($forcewww != "yes") && ($forcewww != "no")) {
		$errMsg = "Invalid force WWW option selected.";
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errMsg = "Invalid server admin email.";
	}
	elseif (!is_valid_domain_name($domain)) {
		$errMsg = "Invalid domain name.";
	}
	else {
		$continue = true;
		if (!empty($aliases)) {
			$aliases = explode(",", $aliases);
			$aliases = array_map("trim", $aliases);
			$aliases = array_map("strtolower", $aliases);
			$aliases = array_map("removeHTTP", $aliases);
			
			foreach ($aliases as $alias) {				
				if (!is_valid_domain_name($alias)) {
					$errMsg = "Invalid domain name included in server aliases.";
					$continue = false;
					
					break;
				}
			}
		}
		
		if ($continue) {
			if (!is_dir($directory)) {
				shell_exec("./shell/apachecphelper mkdir -p " . $directory);
			}
		
			$conf = "<VirtualHost *:80>
	ServerName " . $domain . "
	ServerAlias www." . $domain . "
	ServerAdmin " . $email . "
	
	DocumentRoot " . $directory . "
	
	";
	
			if (!empty($aliases)) {
				foreach ($aliases as $alias) {
					$conf .= "ServerAlias " . $alias . "\n\t";
				}
				$conf .= "\n\n\t";
			}
			
			$conf .= "<Directory " . $directory . ">\n";
			
			if ($forcewww == "yes") {
				$conf .= "\t\tRewriteEngine on
		RewriteCond %{HTTP_HOST} !^$
		RewriteCond %{HTTP_HOST} !^www\. [NC]
		RewriteCond %{HTTPS}s ^on(s)|
		RewriteRule ^ http%1://www.%{HTTP_HOST}%{REQUEST_URI} [R=301,L]\n\n";
			}
			
			$conf .= "\t\tOptions Indexes FollowSymLinks
		AllowOverride " . (($override == "all") ? "All" : "None") . "
		Require all granted
	</Directory>

	ErrorLog \${APACHE_LOG_DIR}/error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>";

			$tmpFilename = getcwd() . "/tmp/acpconf_" . microtime(true) . ".txt";
			file_put_contents($tmpFilename, $conf);

			shell_exec("./shell/apachecphelper mv " . $tmpFilename . " /etc/apache2/sites-available/" . $domain . ".conf");
			shell_exec("./shell/apachecphelper a2ensite " . $domain);
			shell_exec("./shell/apachecphelper service apache2 reload");
			
			$showForm = false;
		}
	}
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

if ($showForm) {
?>
<div class="row" id="addSite">
	<div class="col-lg-12">
		<form method="POST" action="./addsite.php">
			<label>Domain:</label>
			<input type="text" class="form-control" placeholder="example.com" name="domain"<?php if (isset($_POST["domain"])) echo " value=\"" . $_POST["domain"] . "\""; ?>>
			<br>
			<label>Server Aliases:</label>
			<input type="text" class="form-control" placeholder="www is added by default, leave blank for no extra ones. separate multiple with a comma." name="aliases"<?php if (isset($_POST["aliases"])) echo " value=\"" . $_POST["aliases"] . "\""; ?>>
			<br>
			<label>Admin Email:</label>
			<input type="text" class="form-control" placeholder="hello@world.io" name="admin"<?php if (isset($_POST["admin"])) echo " value=\"" . $_POST["admin"] . "\""; ?>>
			<br>
			<label>Directory:</label>
			<input type="text" class="form-control" placeholder="/path/to/website/" name="directory"<?php if (isset($_POST["directory"])) echo " value=\"" . $_POST["directory"] . "\""; ?>>
			<br>
			<label>Allow Override:</label>
			<input type="radio" name="override" value="All" checked="checked"> All
			&nbsp; &nbsp;
			<input type="radio" name="override" value="None"> None
			<br>
			<label>Force WWW:</label>
			<input type="radio" name="forcewww" value="yes"> Yes
			&nbsp; &nbsp;
			<input type="radio" name="forcewww" value="no" checked="checked"> No
			<br>
			<div class="pull-right">
				<button type="submit" class="btn btn-info">
					<span class="glyphicon glyphicon-plus"></span> Add Site
				</button>
			</div>
		</form>
	</div>
</div>
<?php
}
else {
?>
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-success">Your site has been added.</div>
	</div>
</div>
<?php
}

include "footer.php";
?>