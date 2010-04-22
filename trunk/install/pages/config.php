<?php
    $errors = false;

    $template = 
'<?php
    $PW2_CONFIG = array(
        \'db_scheme\' => \'MySQL\',
        \'db_info\' => array(
            \'host\' => \'' . $_POST['hostname'] . '\',
            \'user\' => \'' . $_POST['db_user'] . '\',
            \'pass\' => \'' . $_POST['db_pass'] . '\',
            \'db\' => \'' . $_POST['db_name'] . '\'
        ),
        \'path\' => dirname(__FILE__),
    );

    define(\'PW2_VERSION\', \'' . PW2_VERSION . '\');
    define(\'PW2_PATH\', $PW2_CONFIG[\'path\']);
?>';
        $fh = fopen('../config.php', 'w');
        if($fh === false)
        {
?>
        <div class="section">
            <h1>Failure</h1>
            The installation could not open <tt>config.php</tt> in the root phpWatch directory.  Please assure it exists
            and has read/write permissions.  Refresh the page to retry.
        </div>
<?php
            $errors = true;
        }
        else
        {
            if(fwrite($fh, $template) === false)
            {
?>
        <div class="section">
            <h1>Failure</h1>
            The installation could not write to <tt>config.php</tt> in the root phpWatch directory.  Please assure it
            exists and has read/write permissions.  Refresh the page to retry.
        </div>
<?php
                $errors = true;
            }
        }

        if(!$errors)
        {
?>
<div class="section">
    <h1>Success!</h1>
    The configuration file was written successfully.  Click continue to import the database structure.
</div>
<div class="form-field">
    <?php FormHelpers::startForm('POST', './index.php'); ?>
    <?php FormHelpers::createHidden('page', 'import'); ?>
    <center><?php FormHelpers::createSubmit('Continue'); ?></center>
    <?php FormHelpers::endForm(); ?>
</div>
<?php
        }
?>
