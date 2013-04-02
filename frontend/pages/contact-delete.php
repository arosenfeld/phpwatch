<?php
    $contact = new Contact(intval($_GET['id']));
    if(FormHelpers::donePOST())
    {
        $contact->processDelete($_GET);
        ?>
<div class="message">
    The contact has been deleted. <br />
    <a href="?page=contacts">Return to contacts</a>
</div>
        <?
    }
    else
    {
?>
<div class="form-field">
</div>
<?php FormHelpers::startForm('POST', '?page=contact-delete&id=' . $contact->getId()); ?>
<?php FormHelpers::createHidden('confirmed', '1'); ?>
<center>
    Are you sure you want to delete this contact? All associated channels will also be removed.<br />
    <?php FormHelpers::createSubmit('Yes'); ?>
</center>
<?php FormHelpers::endForm(); ?>
<?php
    }
?>
