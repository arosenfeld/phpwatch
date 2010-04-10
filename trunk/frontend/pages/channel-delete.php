<?php
    $channel = Channel::fetch(intval($_GET['id']));
    $owner = $channel->getOwner();
    if(FormHelpers::donePOST())
    {
        $channel->processDelete($_GET);
        ?>
<div class="message">
    The channel has been deleted. <br />
    <a href="?page=contact&id=<?php p($owner); ?>">Return to contact</a>
</div>
        <?
    }
    else
    {
?>
<div class="form-field">
</div>
<?php FormHelpers::startForm('POST', '?page=channel-delete&id=' . $channel->getId()); ?>
<?php FormHelpers::createHidden('confirmed', '1'); ?>
<center>
    Are you sure you want to delete this channel?<br />
    <?php FormHelpers::createSubmit('Yes'); ?>
</center>
<?php FormHelpers::endForm(); ?>
<?php
    }
?>
