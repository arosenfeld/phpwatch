phpWatch - A Flexible Service Monitoring System
================================================

phpWatch is a flexible service monitoring system. Features include multiple
contact methods for when services malfunction, multiple query methods,
statistics gathering, and a fully extensible architecture for developers.

If you find phpWatch helpful, please consider donating at
[Pledgie](http://www.pledgie.com/campaigns/19717).

Easy Install
-----------------------
1. Change the permissions on `config.php` within the root directory to allow for writing.
2. Navigate to the `install` directory from a web browser and follow the
instructions.
3. Delete the `install` directory and change the permissions of `config.php` to
disallow writing.

Manual Install
-----------------------
If you prefer a manual installation:

1. Fill in the database host, user, password, and name in `config.php`.
2. Import `install/dump.sql` into the specified database.
3. Navigate to the root directory of phpWatch and verify there are no errors.
4. Delete the `install` directory.

Cronjob Setup
-----------------------
To allow phpWatch to query services at a specific interval without human
interaction, a cronjob (or scheduled task in Windows) must be setup to run
`cron.php` at the desired frequency.  Keep in mind, a full path should be used.

For example, to setup a cronjob that runs every 5 minutes, run "crontab -e" and
add the following line to the end of the file:

    */5 * * * * php /path/to/phpwatch/root/directory/cron.php

Bugs & Feature Requests
-----------------------
Please report all bugs and request new features on
[GitHub](https://github.com/arosenfeld/phpwatch/issues).
