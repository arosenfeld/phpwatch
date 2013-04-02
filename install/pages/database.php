<?php FormHelpers::startForm('POST', './index.php'); ?>
<?php FormHelpers::createHidden('page', 'config'); ?>
<div class="section">
    <div class="invalid">This is a release of the 2.x.x branch of phpWatch which uses a database that is incompatible
    with those from 1.x.x.  Make sure the database specified below is entirely empty.</div>
    <h1>Database Information</h1>
    <div class="form-field">
        <strong>Hostname/IP:</strong>
        <div class="descr">Hostname or IP of database server.</div>
        <?php FormHelpers::createText('hostname', 'localhost'); ?>
    </div>
    <div class="form-field">
        <strong>Database Name:</strong>
        <div class="descr">Name of database.</div>
        <?php FormHelpers::createText('db_name', ''); ?>
    </div>
    <div class="form-field">
        <strong>Database User:</strong>
        <div class="descr">User with at least SELECT, INSERT, UPDATE, DELETE, and CREATE privilages.</div>
        <?php FormHelpers::createText('db_user', ''); ?>
    </div>
    <div class="form-field">
        <strong>User Password:</strong>
        <div class="descr">Password for user above.</div>
        <?php FormHelpers::createText('db_pass', ''); ?>
    </div>
    <div class="form-field"><center><?php FormHelpers::createSubmit('Continue'); ?></center></div>
    <?php FormHelpers::endForm(); ?>
</div>
