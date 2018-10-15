#!/bin/sh
xmldbc -P /etc/events/ENABLE.3G.php
service WAN start
service INET.WAN-3 start
exit 0;
