<div class="form-field">
    <strong>Timeout:</strong>
    <div class="descr">The number of seconds until a connection attempt times out.</div>
    <?php FormHelpers::createText('timeout', $monitor->getTimeout(), 'size="3"'); ?>
    <div class="error"><?php FormHelpers::checkError('timeout', $errors); ?></div>
</div>
<div class="form-field">
    <strong>Pattern:</strong>
    <div class="descr">The pattern to find in the response.</div>
    <?php FormHelpers::createText('match_str', $monitor->getMatchString(), 'size="50"'); ?>
    <div class="error"><?php FormHelpers::checkError('match_str', $errors); ?></div>
</div>
<div class="form-field">
    <strong>Match Method:</strong>
    <div class="descr">The method by which the pattern will be checked.</div>
    <ul class="options">
    <li><?php FormHelpers::createRadio('match_method', HttpValidationMonitor::$MATCH_FIND, $monitor->getMatchMethod() ==
    HttpValidationMonitor::$MATCH_FIND ? 'checked="checked"' : ''); ?> Basic match</li>
    <li><?php FormHelpers::createRadio('match_method', HttpValidationMonitor::$MATCH_REGEX, $monitor->getMatchMethod()
    == HttpValidationMonitor::$MATCH_REGEX ? 'checked="checked"' : ''); ?> Use regular expressions (Will error if
    invalid pattern.  See <a href="http://www.php.net/manual/en/pcre.pattern.php" target="_new">Reference</a>)</li>
    </ul>
    <div class="error"><?php FormHelpers::checkError('match_method', $errors); ?></div>
</div
><div class="form-field">
    <strong>Condition:</strong>
    <div class="descr">What condition must be met for the monitor to be considered online?</div>
    <ul class="options">
    <li><?php FormHelpers::createRadio('mode', HttpValidationMonitor::$MODE_DOES_CONTAIN, $monitor->getMode() ==
    HttpValidationMonitor::$MODE_DOES_CONTAIN ? 'checked="checked"' : ''); ?> The monitor <strong>must</strong> contain
    the pattern.</li>
    <li><?php FormHelpers::createRadio('mode', HttpValidationMonitor::$MODE_DOESNT_CONTAIN, $monitor->getMode() ==
    HttpValidationMonitor::$MODE_DOESNT_CONTAIN ? 'checked="checked"' : ''); ?> The monitor must <strong>not</strong>
    contain the pattern.</li>
    </ul>
    <div class="error"><?php FormHelpers::checkError('mode', $errors); ?></div>
</div>
