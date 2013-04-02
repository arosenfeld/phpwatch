<div class="menu">
    <ul class="page-menu">
        <li>
            <?php FormHelpers::startForm('GET', '?page=contact'); ?>
            <?php FormHelpers::createHidden('page', 'contact'); ?>
            <?php FormHelpers::createSubmit('New Contact'); ?>
            <?php FormHelpers::endForm(); ?>
        </li>
    </ul>
</div>
<div class="section">
    <h1>Contacts</h1>
    <?php
        foreach(GuiHelpers::getAllChannels() as $id => $info) : 
            $contact = new Contact($id);
    ?>
    <div class="info">
        <strong><?php p($info['name']); ?></strong>
        <div class="right"><a href="?page=contact&id=<?php p($contact->getId()); ?>">Edit</a> - <a
        href="?page=contact-delete&id=<?php p($contact->getId()); ?>">Delete</a></div>
    </div>
    <ul class="information">
        <?php
            foreach($info['channels'] as $chan) :
                $chandle = Channel::fetch(intval($chan['id']));
        ?>
        <li>
            <?php p($chandle->getName()); ?>
            <div class="descr"><?php p($chandle); ?></div>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php
        endforeach;
    ?>
    </div>

