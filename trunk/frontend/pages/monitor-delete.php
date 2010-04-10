<?php
    $monitor = Monitor::fetch(intval($_GET['id']));
    if(FormHelpers::donePOST())
    {
        $monitor->processDelete($_GET);
        ?>
<div class="message">
    The monitor has been deleted. <br />
    <a href="?page=monitors">Return to monitors</a>
</div>
        <?
    }
    else
    {
?>
<div class="form-field">
</div>
<?php FormHelpers::startForm('POST', '?page=monitor-delete&id=' . $monitor->getId()); ?>
<?php FormHelpers::createHidden('confirmed', '1'); ?>
<center>
    Are you sure you want to delete this monitor?<br />
    <?php FormHelpers::createSubmit('Yes'); ?>
</center>
<?php FormHelpers::endForm(); ?>
<?php
    }
?>
