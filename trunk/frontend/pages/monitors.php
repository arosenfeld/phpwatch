<div class="menu">
    <ul class="page-menu">
        <li>
            <?php
                FormHelpers::startForm('GET', '?page=monitor');
                FormHelpers::createHidden('page', 'monitor');
            ?>
            <?php
                $options = array();
                foreach($GLOBALS['monitor_types'] as $type)
                {
                    $o = new $type();
                    $options[] = FormHelpers::getOption($o->getName(), $type);
                }
                FormHelpers::createSelect('type', $options);
                FormHelpers::createSubmit('New Monitor');
            ?>
            <?php FormHelpers::endForm(); ?>
        </li>
    </ul>
</div>
<div class="section">
    <h1>Monitors</h1>
    <?php foreach(GuiHelpers::getMonitors() as $monitor) : ?>
    <?php
        switch($monitor->getStatus())
        {
            case STATUS_ONLINE :
                p('<h2 class="online">Online</h2>');
                break;
            case STATUS_OFFLINE :
                p('<h2 class="offline">Offline</h2>');
                break;
            case STATUS_PAUSED :
                p('<h2 class="waiting">Paused</h2>');
                break;
            case STATUS_DOWNTIME :
                p('<h2 class="waiting">Scheduled Downtime</h2>');
                break;
            case STATUS_UNPOLLED :
                p('<h2 class="waiting">Unpolled</h2>');
                break;
        }
    ?>
    <div class="info">
        <?php $monitor->getAlias() ? p('<strong>' . $monitor->getAlias() . '</strong> - ') : ''; ?><?php p($monitor->getHostname()); ?>:<?php p($monitor->getPort()); ?>
        <div class="right">
            <a href="?page=monitor&id=<?php p($monitor->getId()); ?>">Edit</a> -
            <a href="?page=monitor-delete&id=<?php p($monitor->getId()); ?>">Delete</a> - 
            <a href="?page=query&id=<?php p($monitor->getId()); ?>">Re-query</a>
        </div>
    </div>
    <ul class="information">
        <li><strong>Contacts:</strong>
        <?php
            $contacts = GuiHelpers::getContactsByMonitor($monitor);
            if(is_array($contacts)) :
                foreach($contacts as $i => $c) :
                    if($i > 0) : p(', '); endif;
        ?>
                <a href="?page=contact&edit=<?php p($c['id']); ?>"><?php p($c['name']); ?></a>
            <?php 
                endforeach;
            else :
                p('None');
            endif;
            ?>
        <li><strong>Last Query:</strong>
        <?php 
            if($monitor->getStatus() == STATUS_UNPOLLED)
                p('N/A');
            else
                p(GuiHelpers::formatDateLong($monitor->getLastQuery()));
        ?>
        </li>
    </ul>
    <?php endforeach; ?>
</div>
