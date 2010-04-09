<?php
    print_r($_GET);
    $monitor = Monitor::fetch(intval($_GET['id']));
    if(FormHelpers::donePOST())
    {
        print_r($monitor->processDelete($_GET));
    }
    else
    {
?>
<div class="form-field">
</div>
<?php FormHelpers::startForm('POST', '?page=monitor-delete&id=' . $monitor->getId()); ?>
<?php FormHelpers::createHidden('confirmed', '1'); ?>
<center>
    Are you sure you want to delete the "<?php p($monitor->getAlias()); ?>" monitor?<br />
    <?php FormHelpers::createSubmit('Yes'); ?>
</center>
<?php FormHelpers::endForm(); ?>
<?php
    }
?>
