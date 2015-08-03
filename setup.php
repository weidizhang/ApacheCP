<?php
/*
 * ApacheCP
 * @author Weidi Zhang http://github.com/ebildude123
 * @license CC BY-NC-SA 4.0 (See LICENSE file)
 */

if (php_sapi_name() === "cli") {
	$whoami = shell_exec("whoami");
	if (trim($whoami) == "root") {
		echo "Compiling apachecphelper...\n";
		shell_exec("gcc ./shell/apachecphelper.c -o ./shell/apachecphelper");
		if (!file_exists("./shell/apachecphelper")) {
			echo "Do you have gcc installed? apachecphelper failed to compile.\n";
			echo "ApacheCP setup failed.\n";
			die();
		}
		echo "Done.\n\n";
		
		echo "Setting permissions...\n";
		shell_exec("chown root ./shell/apachecphelper");
		shell_exec("chmod u=rwx,go=xr,+s ./shell/apachecphelper");
		echo "Done.\n\n";
		
		echo "Setting up tmp folder...\n";
		if (!is_dir("./tmp")) {
			mkdir("./tmp", 0777);
			shell_exec("chmod 0777 ./tmp"); // above doesn't set to 0777 sometimes
		}
		shell_exec("chown www-data ./tmp");
		echo "Done.\n\n";
		
		echo "Enabling apache rewrite mod...\n";
		shell_exec("a2enmod rewrite");
		shell_exec("service apache2 restart");
		echo "Done.\n\n";
		
		echo "\nSetup your admin account\n";
		echo "=========================\n";
		echo "Enter your desired username: ";
		$username = trim(read_stdin());
		echo "Enter your desired password: ";
		$password = trim(read_stdin());
		
		$adminFile = "<?php \$username = \"" . addslashes($username) . "\"; \$password = \"" . hash("whirlpool", $password) . "\"; ?>";
		file_put_contents("./inc.admin.php", $adminFile);
		
		echo "\nApacheCP setup complete.\n";
		echo "You can delete setup.php now, but it is optional as it can only run in command line mode.\n";
	}
	else {
		echo "ApacheCP setup script must be ran as root.\n";
	}
}

function read_stdin()
{
	$fr = fopen("php://stdin", "r");
	$input = fgets($fr, 128);
	$input = rtrim($input);
	fclose($fr);
	return $input;
}
?>