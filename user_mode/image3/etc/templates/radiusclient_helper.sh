#!/bin/sh
echo [$0] $1  ... > /dev/console
PHPFILE="/etc/templates/radiusclient_helper.php"
xmldbc -A $PHPFILE -V FLAG=$1 > /var/run/radiusclient_helper.sh
sh /var/run/radiusclient_helper.sh > /dev/console
