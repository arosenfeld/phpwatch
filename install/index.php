<?php
    require_once(dirname(__FILE__) . '/../config.php');
    require_once(dirname(__FILE__) . '/../frontend/GuiHelpers.php');
    require_once(dirname(__FILE__) . '/../frontend/FormHelpers.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="content-language" content="en">
		<title>phpWatch :: The open-source service monitor</title>
		<link rel="stylesheet" type="text/css" href="../frontend/screen.css">
		<link rel="stylesheet" type="text/css" href="./install.css">
	</head>
	<body>
		<div id="content">
			<div id="left-column">				
				<div id="subheader">
					<img src="../frontend/images/logo.jpg">
					<p>Installer</p>
				</div>
                <?php
                    if($_POST)
                        $page = $_POST['page'];
                    else
                        $page = 'start';
                    require_once('./pages/' . $page . '.php');
                ?>
			</div>
			
			<div class="right-block">
                <div>
                    <p class="side-title">Welcome!</p>
                    <p>Thank you for your interest in phpWatch 2.  This is an open-source project that entirely relies on
                    users for support.  If you find phpWatch useful, please consider donating!</p>
                    <a href="http://sourceforge.net/donate/index.php?group_id=233530" class="plain" target="_new"><img
                    src="http://images.sourceforge.net/images/project-support.jpg" class="donate" alt="Support This Project"
                    /></a>
                </div>
			</div>
		</div>
	</body>
</html>
