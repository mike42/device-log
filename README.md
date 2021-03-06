Device Log
==========

Device Log is a web app for logging device maintenance. It allows IT staff to keep track of an organisation's "fleet" of devices, as well as maintaining an inventory of spares.

A full history of each device is kept, so that technicians can troubleshoot problems with adequate knowledge of a device's past repairs and known issues.

Setup
-----

Install PHP's LDAP and Imagick functions:

        sudo apt-get install php5-ldap php5-imagick
        sudo service apache2 restart

Clone the repo, then:
* Add a symbolic link to the 'dl' folder from your webroot.
* Install the database
* Open site/config.example.php, add options and save to site/config.php

Post-setup
----------
Test receipt printing and file uploads. File permissions and protocol issues can make these a bit tricky.

Credits
-------
* Background image on login page is from [Wikimedia Commons](http://commons.wikimedia.org/wiki/File:Purekkari_neemel.jpg)

Client-side libraries used:
* [bootstrap](http://getbootstrap.com/‎)
* [backbone.js](http://backbonejs.org/‎)
* [jQuery](http://jquery.com/‎)
* [Dropzone.js](http://www.dropzonejs.com/‎)
* [Bootstrap 3 Typeahead](https://github.com/bassjobsen/Bootstrap-3-Typeahead)

