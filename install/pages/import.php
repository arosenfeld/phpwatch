<?php
    require_once(PW2_PATH . '/src/db_schemes/' . $PW2_CONFIG['db_scheme'] . '.php');
    if(($f = file_get_contents('dump.sql')) === false)
    {
?>
    <div class="section">
        <h1>Failure</h1>
        The installation could not read <tt>install/dump.sql</tt>.  Please assure it exists and has read permissions.
        Refresh the page to retry.
    </div>
<?php
    }
    else
    {
        $GLOBALS['PW_DB'] = new $PW2_CONFIG['db_scheme']($PW2_CONFIG['db_info']['host'], $PW2_CONFIG['db_info']['db'], $PW2_CONFIG['db_info']['user'], $PW2_CONFIG['db_info']['pass']);
        if($GLOBALS['PW_DB']->connect() === false)
        {
?>
    <div class="section">
        <h1>Failure</h1>
        The installation could not connect to the database.  Please assure the database information is correct.
    </div>
<?php
        }
        else
        {
            $cmds = explode(";\n", $f);
            $errors = false;
            foreach($cmds as $c)
            {
                if(strlen($c) > 0 && $GLOBALS['PW_DB']->query($c) == false)
                {
                    $errors = true;
                    echo mysql_error();
                }
            }
            if($errors)
            {
?>
    <div class="section">
        <h1>Failure</h1>
        The installation could not query to the database.  Please assure the database information is correct and the user
        has SELECT, INSERT, UPDATE, DELETE, and CREATE permissions.
    </div>
<?php
            }
            else
            {
?>
    <div class="section">
        <h1>Success!</h1>
        Installation is complete!  Remember to delete the install directory and change
        the permissions on <tt>config.php</tt>.  Click <a href="../index.php">here</a> to begin using phpWatch.
    </div>
<?php
            }
        }
    }
?>
