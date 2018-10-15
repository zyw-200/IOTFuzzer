#!/bin/sh
xmldbc -P /etc/events/DISABLE.3G.php
service DEVICE.LAYOUT stop
service INET.WAN-3  stop
service WAN stop

exit 0;
