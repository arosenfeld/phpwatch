<div class="menu">
    <ul class="page-menu">
        <li><a href="#">Add Monitor</a></li>
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
            case STATUS_WAITING :
                p('<h2 class="waiting">Scheduled Downtime</h2>');
                break;
            case STATUS_UNPOLLED :
                p('<h2 class="waiting">Unpolled</h2>');
                break;
        }
    ?>
    <div class="info">
        <strong><?php p($monitor->getAlias()); ?></strong> - <?php p($monitor->getHostname()); ?>:<?php p($monitor->getPort()); ?>
        <div class="right"><a href="?page=monitor&id=<?php p($monitor->getId()); ?>">Edit</a> - <a href="#">Delete</a></div>
    </div>
    <ul class="information">
        <li><strong>Contacts:</strong>
        <?php foreach(GuiHelpers::getContactsByMonitor($monitor) as $i => $c) : ?>
            <?php if($i > 0) : p(', '); endif; ?>
            <a href="?page=contact&edit=<?php p($c['id']); ?>"><?php p($c['name']); ?></a>
        <?php endforeach; ?>
        <li><strong>Last Query:</strong> <?php p(GuiHelpers::formatDateLong($monitor->getLastQuery())); ?></li>
    </ul>
    <?php endforeach; ?>
</div>
