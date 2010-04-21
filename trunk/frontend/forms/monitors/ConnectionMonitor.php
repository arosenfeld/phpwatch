<div class="form-field">
    <strong>Timeout:</strong>
    <div class="descr">The number of seconds until a connection attempt times out.</div>
    <?php FormHelpers::createText('timeout', $monitor->getTimeout(), 'size="3"'); ?>
    <div class="error"><?php FormHelpers::checkError('timeout', $errors); ?></div>
</div>
