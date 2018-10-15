#!/bin/sh
echo [$0] ... > /dev/console
/usr/sbin/radlogin -f /var/run/radious.conf -s /etc/templates/radiusclient_helper.sh
