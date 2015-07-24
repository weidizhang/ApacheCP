<?php
/*
 * ApacheCP
 * @author Weidi Zhang http://github.com/ebildude123
 * @license CC BY-NC-SA 4.0 (See LICENSE file)
 */

session_start();

function isLoggedIn() {
	return (isset($_SESSION["admin"]) && @$_SESSION["admin"] === 1);
}

function redirectToLogin() {
	header("Location: ./login.php");
	die();
}

function redirectToHome() {
	header("Location: ./index.php");
	die();
}
?>