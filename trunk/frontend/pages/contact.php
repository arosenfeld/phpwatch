<?php
    $show_form = true;
    if(is_numeric($_GET['id']))
    {
        $contact = new Contact(intval($_GET['id']));
    }
    else
    {
        $contact = new Contact();
    }

    $errors = array();

    if(FormHelpers::donePOST())
    {
        $errors = $contact->processAddEdit($_POST);
        if(sizeof($errors) == 0)
        {
            $contact->saveToDb();
            $show_form = false;
?>
<div class="message">
    The contact has been saved. <br />
    <a href="?page=contact&id=<?php p($contact->getId()); ?>">Return to "<?php p($contact->getName()); ?>"</a> | 
    <a href="?page=contacts">Return to contacts list</a>
</div>
<?php
        }
    }
    
    if($show_form)
    {
?>
<div class="section">
    <h1><?php p($contact->getId() > 0 ? 'Edit' : 'Add'); ?> Contact</h1>
    <h2>General Settings</h2>
    <?php 
        FormHelpers::startForm('POST', '?page=contact&id=' . $contact->getId(), 'name="general"');
    ?>
        <div class="form-field">
            <strong>Name:</strong>
            <div class="descr">Name of contact.</div>
            <?php FormHelpers::createText('name', $contact->getName(), 'size="30"'); ?>
            <div class="error"><?php FormHelpers::checkError('name', $errors); ?></div>
        </div>
    <div class="form-field"><center><?php FormHelpers::createButton('Submit', 'onClick="document.general.submit()"'); ?></center></div>
    <?php
        FormHelpers::endForm();
        if($contact->getId() != null) :
    ?>
    <h2>Notification Channels</h2>
    <strong>Description:</strong>
    <div class="type-descr">Additions, deletions, and modifications of channels will be saved automatically.</div>
    <?php
        if($contact->getId() != null)
        {
            $existing = GuiHelpers::getAllChannels($contact->getId());
            $existing = $existing[$contact->getId()]['channels'];
            foreach($existing as $e) :
                $chandle = Channel::fetch(intval($e['id']));
                ?>
                    <div class="info">
                        <?php p($chandle->getName()); ?> 
                        <div class="right">
                            <a href="?page=channel&id=<?php p($e['id']); ?>">Edit</a> -
                            <a href="?page=channel-delete&id=<?php p($e['id']); ?>">Delete</a>
                        </div>
                        <div class="descr"><?php p($chandle); ?></div>
                    </div>
                <?php
            endforeach;
        }
    ?>
    <div class="form-field">
    <?php
        FormHelpers::startForm('GET', '?page=channel', 'name="newchan"');
        FormHelpers::createHidden('page', 'channel');
        FormHelpers::createHidden('contact_id', $contact->getId());
        $options = array();
        foreach($GLOBALS['channel_types'] as $type)
        {
            $o = new $type();
            $options[] = FormHelpers::getOption($o->getName(), $type);
        }
        FormHelpers::createSelect('type', $options);
        FormHelpers::createSubmit('Add Notification Channel');
        FormHelpers::endForm();
    ?>
    </div>
    <?php endif; ?>
</div>
<?php
    }
?>
