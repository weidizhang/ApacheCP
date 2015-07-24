<?php
/*
 * ApacheCP
 * @author Weidi Zhang http://github.com/ebildude123
 * @license CC BY-NC-SA 4.0 (See LICENSE file)
 */

if (count(get_included_files()) <= 1) {
	die();
}
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<!--[if IE]>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<![endif]-->
		<title><?php echo $pageName; ?> | ApacheCP</title>
		<link href="assets/css/bootstrap.css" rel="stylesheet">
		<link href="assets/css/font-awesome.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
		<link href="assets/css/apachecp.css" rel="stylesheet">
		<script src="assets/js/jquery-1.11.1.js"></script>
		<script src="assets/js/bootstrap.js"></script>
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="navbar navbar-inverse set-radius-zero">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="./">
						ApacheCP
					</a>
				</div>

				<div class="left-div">
					<div class="user-settings-wrapper">
						<ul class="nav">
						</ul>
					</div>
				</div>
			</div>
		</div>
		<section class="menu-section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="navbar-collapse collapse ">
							<ul id="menu-top" class="nav navbar-nav navbar-right">
							<?php
							if (isLoggedIn()) {
							?>
								<li><a <?php if ($pageName == "Dashboard") echo "class=\"menu-top-active\" "; ?>href="./index.php">Dashboard</a></li>
								<li><a <?php if (strpos($pageName, "Site") !== false) echo "class=\"menu-top-active\" "; ?>href="./sites.php">Sites</a></li>
								<li><a <?php if ($pageName == "Logout") echo "class=\"menu-top-active\" "; ?>href="./logout.php">Logout</a></li>
							<?php
							}
							else {
							?>
								<li><a class="menu-top-active" href="./login.php">Login</a></li>
							<?php
							}
							?>
							</ul>
						</div>
					</div>

				</div>
			</div>
		</section>
		<div class="content-wrapper">
			<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4 class="page-head-line"><?php echo $pageName; ?></h4>
				</div>
			</div>