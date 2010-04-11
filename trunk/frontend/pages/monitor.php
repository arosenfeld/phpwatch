<?php
    $show_form = true;
    if(is_numeric($_GET['id']))
    {
        $monitor = Monitor::fetch(intval($_GET['id']));
    }
    else
    {
        $monitor = new $_GET['type']();
    }

    $errors = array();

    if(FormHelpers::donePOST())
    {
        $errors = $monitor->processAddEdit($_POST);
        if(sizeof($errors) == 0)
        {
            $monitor->saveToDb();
            $show_form = false;
?>
<div class="message">
    The monitor has been saved. <br />
    <a href="?page=monitors">Return to monitors</a>
</div>
<?php
        }
    }

    if($show_form)
    {
?>
<div class="section">
    <h1><?php p($monitor->getId() > 0 ? 'Edit' : 'Add'); ?> Monitor</h1>
    <h2>Generic Settings</h2>
    <?php 
        if($monitor->getId() != 0)
            FormHelpers::startForm('POST', '?page=monitor&id=' . $monitor->getId());
        else
            FormHelpers::startForm('POST', '?page=monitor&type=' . $_GET['type']);
    ?>
    <div class="form-field">
        <strong>Hostname:</strong>
        <div class="descr">Hostname or IP to monitor.</div>
        <?php FormHelpers::createText('hostname', $monitor->getHostname(), 'size="30"'); ?>
        <div class="error"><?php FormHelpers::checkError('hostname', $errors); ?></div>
    </div>

    <div class="form-field">
        <strong>Port:</strong>
        <div class="descr">Port on above hostname/IP to monitor.</div>
        <?php FormHelpers::createText('port', $monitor->getPort(), 'size="5"'); ?>
        <div class="error"><?php FormHelpers::checkError('port', $errors); ?></div>
    </div>

    <div class="form-field">
        <strong>Alias:</strong>
        <div class="descr">Optional alias for this monitor.</div>
        <?php FormHelpers::createText('alias', $monitor->getAlias()); ?>
        <div class="error"><?php FormHelpers::checkError('alias', $errors); ?></div>
    </div>

    <div class="form-field">
        <strong>Fail Threshold:</strong>
        <div class="descr">Number of sequential failures required to send notifications.</div>
        <?php FormHelpers::createText('fail_threshold', $monitor->getFailThreshold(), 'size="3"'); ?>
        <div class="error"><?php FormHelpers::checkError('fail_threshold', $errors); ?></div>
    </div>
    <h2>Status</h2>
    <div class="form-field">
        <ul class="options">
            <li><?php FormHelpers::createRadio('status', STATUS_UNPOLLED, $monitor->getStatus() == STATUS_ONLINE ||
            $monitor->getStatus() == STATUS_OFFLINE || $monitor->getStatus() == STATUS_UNPOLLED ? 'checked="checked"' :
            ''); ?> Running
            <div class="descr">Query, log uptime,  and send notifications for this monitor.</div>
            </li>
            <li><?php FormHelpers::createRadio('status', STATUS_PAUSED, $monitor->getStatus() == STATUS_PAUSED ?
            'checked="checked"' : ''); ?> Paused</li>
            <div class="descr">Do not query, log uptime, nor send notifications for this monitor indefinitely.</div>
            <li>
            <?php FormHelpers::createRadio('status', STATUS_DOWNTIME, $monitor->getStatus() == STATUS_DOWNTIME ?
            'checked="checked"' : ''); ?> Schedule Downtime - Starting in
            <?php 
                list($start_h, $start_m) = GuiHelpers::getHoursMinutes($monitor->getDowntimeStart());
                list($end_h, $end_m) = GuiHelpers::getHoursMinutes($monitor->getDowntimeEnd());
                FormHelpers::createText('downtime_start_hours', $start_h, 'size="2"');
                p(' hours, ');
                FormHelpers::createText('downtime_start_minutes', $start_m, 'size="2"');
                p(' minutes, lasting for ');
                FormHelpers::createText('downtime_end_hours', $end_h, 'size="2"');
                p(' hours, ');
                FormHelpers::createText('downtime_end_minutes', $end_m, 'size="2"');
                p(' minutes.');
            ?>
            <div class="error"><?php FormHelpers::checkError('interval', $errors); ?></div>
            <div class="descr">Do not query, log uptime, nor send notifications for this monitor starting at the
            specified time for the specified interval.</div>
            </li>
        </ul>
    </div>

    <h2>"<?php p($monitor->getName()); ?>" Specific Settings</h2>
    <strong>Description:</strong>
    <div class="type-descr"><?php p($monitor->getDescription()); ?></div>
    <?php require_once(PW2_PATH . '/frontend/forms/monitors/' . get_class($monitor) . '.php'); ?>

    <h2>Notification Channels</h2>
    <div class="type-descr">The checkboxes below toggle who shall be contacted if this monitor is found to be offline.
    They may also be changed within their respective contact page.</div>
    <div class="form-field">
    <?php
        foreach(GuiHelpers::getAllChannels() as $id => $info) : 
            $channels = $info['channels'];
            if(sizeof($channels) > 0) :
                $c = new Contact($id);
    ?>
    <strong><?php p($c->getName()); ?></strong>
    <ul class="options">
        <?php
            foreach($channels as $chan) :
                $chandle = Channel::fetch(intval($chan['id']));
                if(array_search($chandle->getId(), $monitor->getChanIds()) !== false)
                    $checked = true;
                else
                    $checked = false;
        ?>
            <li><?php
                FormHelpers::createCheckbox('notification_channels[]', $chandle->getId(), $checked ? 'checked="checked"'
                : '');
                p('<strong>' . $chandle->getName() . ':</strong> ' . $chandle); ?></li>
        <?php endforeach; ?>
    </ul>
    <?php
            endif;
        endforeach;
    ?>
    </div>
    <div class="form-field"><center><?php FormHelpers::createSubmit('Submit'); ?></center></div>
    <?php FormHelpers::endForm(); ?>
</div>
<?php
    }
?>
