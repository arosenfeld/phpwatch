<div class="type-descr">The fields below expect string that will be used to format SMS messages.  The format is specified
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
    <strong>Phone Number:</strong>
    <div class="descr">Phone number, no dashes (e.g. 1234567890).</div>
    <?php FormHelpers::createText('number', $channel->getNumber()); ?>
    <div class="error"><?php FormHelpers::checkError('number', $errors); ?></div>
</div>
<div class="form-field">
    <strong>Carrier:</strong>
    <div class="descr">Mobile carrier.</div>
    <?php
        $options = array();
        foreach(array_keys(SmsChannel::$carriers) as $c)
            $options[] = FormHelpers::getOption($c, $c, ($channel->getCarrier() == $c ? 'selected="selected"' : null));
        FormHelpers::createSelect('carrier', $options);
    ?>
    <div class="error"><?php FormHelpers::checkError('number', $errors); ?></div>
</div>
