<?php
    $errors = false;
?>
<div class="message left">
    <p>Thank you for choosing <strong>phpWatch</strong> as your service monitoring solution.  Before beginning the
    installation process, please assure that the following steps are compelete:
    <ul>
        <li><strong>config.php Writable: </strong> 
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
