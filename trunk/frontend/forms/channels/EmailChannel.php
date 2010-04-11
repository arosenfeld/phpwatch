<div class="type-descr">The fields below expect string that will be used to format e-mail messages.  The format is specified
by <a href="http://php.net/manual/en/function.sprintf.php" target="_new">sprintf</a> and is passed the parameters:
hostname, port, and alias.</div>
<div class="form-field">
    <strong>Subject Format:</strong>
    <div class="descr">Format of subject to send.</div>
    <?php
        if($channel->getSubjectFormat())
            $subj = $channel->getSubjectFormat();
        else
            $subj = 'Service Offline';
        FormHelpers::createText('subject', $subj);
    ?>
    <div class="error"><?php FormHelpers::checkError('subject', $errors); ?></div>
    </div>
<div class="form-field">
    <strong>Message Format:</strong>
    <div class="descr">Format of message to send.</div>
    <?php
        if($channel->getMessageFormat())
            $msg = $channel->getMessageFormat();
        else
            $msg = '%s:%d (%s) is offline.';
        FormHelpers::createTextArea('message', $msg, 'rows="5" cols="100"');
    ?>
    <div class="error"><?php FormHelpers::checkError('message', $errors); ?></div>
</div>
<div class="form-field">
    <strong>Address:</strong>
    <div class="descr">Properly formatted e-mail address.</div>
    <?php FormHelpers::createText('address', $channel->getAddress(), 'size="40"'); ?>
    <div class="error"><?php FormHelpers::checkError('address', $errors); ?></div>
</div>
