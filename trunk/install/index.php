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
					<p class="right-side">Version: v<?php p(PW2_VERSION); ?></p>
                    <?php
                        list($good, $info) = GuiHelpers::checkVersion();
                        if($good)
                        {
                            p('<p class="version-notice-good">Up to date</p>');
                        }
                        else
                        {
                            if($info === false)
                                p('<p class="version-notice-bad">Unable to check version</p>');
                            else
                                p('<p class="version-notice-bad">Old version.  Update <a href="' . $info . '"
                                target="_new">here</a></p>');
                        }
                    ?>
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
				<p class="side-title">Welcome!</p>
				<p>Some text...</p>
			</div>
		</div>
	</body>
</html>
