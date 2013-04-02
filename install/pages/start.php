<?php
    $errors = false;
?>
<div class="section">
    <h1>phpWatch Installer</h1>
    <p>Thank you for choosing <strong>phpWatch</strong> as your service monitoring solution.  Before beginning the
    installation process, please assure that the following steps are compelete:</p>
    <ul>
        <li>The database for installation is emptied of tables from phpWatch 1.x.x or other applications.</li>
        <li>config.php is writable:
        <?php
            if(is_writable('../config.php'))
            {
                p('<div class="valid">Yes</div>');
            }
            else
            {
                $errors = true;
                p('<div class="invalid">No</div>');
            }
        ?>
        </li>
    </ul>
    <?php FormHelpers::startForm('POST', './index.php'); ?>
    <?php FormHelpers::createHidden('page', 'database'); ?>
    <center><?php FormHelpers::createSubmit('Continue', ($errors ? 'disabled="disabled"' : null)); ?></center>
    <?php FormHelpers::endForm(); ?>
</div>
