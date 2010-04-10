<?php
    require_once('../common.php');
    require_once(PW2_PATH . '/frontend/GuiHelpers.php');
    require_once(PW2_PATH . '/frontend/FormHelpers.php');
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
					<p><?php p(GuiHelpers::getPage($_GET['page'])); ?></p>
					<p class="right-side">Current version: v<?php p(PW2_VERSION); ?></p>
				</div>
                <div class="menu">
                <ul>
                    <li><a href="?page=monitors">Monitors</a></li>
                    <li><a href="?page=contacts">Contacts</a></li>
                    <li><a href="?page=config">Configuration</a></li>
                </ul>
                </div>
                <?php require('./pages/' . GuiHelpers::getPage($_GET['page']) . '.php'); ?>
			</div>
			
			<div class="right-block">
				<p class="side-title">Total Monitors:</p>
				<p><?php p(GuiHelpers::getStatistic('monitor_count')); ?></p>
				<p class="side-title">Total Contacts:</p>
				<p><?php p(GuiHelpers::getStatistic('contact_count')); ?></p>
				<p class="side-title">Total Log Entries:</p>
				<p><?php p(GuiHelpers::getStatistic('log_count')); ?></p>
				<p class="side-title">Last Offline:</p>
                <p><?php p(GuiHelpers::formatDateLong(GuiHelpers::getStatistic('last_offline'))); ?></li>
			</div>
		</div>
	</body>
</html>
