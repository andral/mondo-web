Mondo Web
=========

### Overview

Monto Web is a small web UI to display the latest mondo backups.

![screenshot](/lib/mondo-web.png)

It's a nasty and ugly PHP hack but it works for us to have a quick overview of all the backups.

All our physical servers run mondo once a week to a nfs share.
Every server creates a directory on the nfs share with the following format:

    hostname-DD-MM-YYYY-HHMM

We also use mod_auth_ntlm_winbind for SSO. Frontend framework is twitter's awesome bootstrap.
The list of our servers is pulled from RHN Satellite using the XML API. Should also work for Spacewalk.
We filter out virtual servers because they don't run mondo rescue.

### Installation
* Have your servers run mondo periodicaly and store the files to an nfs server. Our cron job is in lib/
* Configure Apache with NTLM or comment it out in index.php
* Edit index.php and put your users in $_users or comment it out in index.php
* Edit inc/mondo.php and put in your satellite url and login


### See also
* [Mondo Rescue](http://www.mondorescue.org/)
* [mod_auth_ntlm_winbind](https://github.com/Rich2k/adLDAP/wiki/Mod-auth-ntlm-winbind)
* [Twitter Bootstrap](http://getboostrap.com)
* [RHN Satellite](http://www.redhat.com/products/enterprise-linux/rhn-satellite/)
