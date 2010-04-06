<?php
    require_once('../common.php');
    require_once('./helpers.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="content-language" content="en">
		<title>phpWatch :: The open-source service monitor</title>
		<link rel="stylesheet" type="text/css" href="screen.css">
	</head>
	<body>
		<div id="content">
			<div id="left-column">				
				<div id="subheader">
					<img src="images/logo.jpg">
					<p>System Dashboard</p>
					<p class="right-side">Current version: v2.0</p>
				</div>
                <div id="menu">
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Contacts</a></li>
                    <li><a href="#">Configuration</a></li>
                </ul>
                </div>
				<div class="section">
					<h1>Monitors</h1>
                    <?php foreach(GuiHelpers::getMonitors() as $monitor) : ?>
                    <h2><?php p($monitor->getAlias()); ?></h2> (IP: <?php p($monitor->getHostname()); ?>, port <?php p($monitor->getPort()); ?>)
					<ul class="information">
						<li><strong>Contacts:</strong> Aaron Rosenfeld, Person Two, Person Three, <a href="#">and 5 others...</a></li>
                        <li><strong>Last Offline:</strong> February 15, 2010 13:22 GMT</li>
					</ul>
                    <?php endforeach; ?>
				</div>
			</div>
			
			<div class="right-block">
				<p class="side-title">Total Monitors:</p>
				<p>12</p>
				<p class="side-title">Last Offline:</p>
				<p>February 15, 2010 13:22 GMT</p>
				<p class="side-title">Total Log Messages:</p>
				<p>123,418,929</p>
				<p class="side-title">Avg. Uptime:</p>
                <p>78.42%</p>
			</div>
		</div>
	</body>
</html>
