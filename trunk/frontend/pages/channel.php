<?php
    $show_form = true;
    if(is_numeric($_GET['id']))
    {
        $channel = Channel::fetch(intval($_GET['id']));
    }
    else
    {
        $channel = new $_GET['type']();
        $channel->setOwner(intval($_GET['contact_id']));
    }

    $errors = array();

    if(FormHelpers::donePOST())
    {
        $errors = $channel->processAddEdit($_POST);
        if(sizeof($errors) == 0)
        {
            $channel->saveToDb();
            $show_form = false;
?>
<div class="message">
    The channel has been saved. <br />
    <a href="?page=contact&id=<?php p($channel->getOwner()); ?>">Return to contact</a>
</div>
<?php
        }
    }
    
    if($show_form)
    {
?>
<div class="section">
    <h1><?php p($channel->getId() > 0 ? 'Edit' : 'Add'); ?> Channel</h1>
    <h2>Information</h2>
    <div class="form-field"><strong>Contact:</strong> <?php $c = new Contact($channel->getOwner());
    p($c->getName()); ?></div>
    <div class="form-field"><strong>Channel Type: </strong><?php p($channel->getName()); ?>
    <div class="form-field"><strong>Description: </strong><?php p($channel->getDescription()); ?></div>
    </div>

    <h2>Configuration</h2>
    <?php
        if($channel->getId() != null)
        {
            FormHelpers::startForm('POST', '?page=channel&id=' . $channel->getId());
        }
        else
        {
            FormHelpers::startForm('POST', '?page=channel&contact_id=' . $channel->getOwner() . '&type=' . $_GET['type']);
        }
        require_once(PW2_PATH . '/frontend/forms/channels/' . get_class($channel) . '.php');
    ?>
    <div class="form-field"><center><?php FormHelpers::createSubmit('Submit'); ?></center></div>
    <?php FormHelpers::endForm(); ?>
</div>
<?php
    }
?>
