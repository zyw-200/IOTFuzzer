#!/bin/sh
echo [$0]  ... > /dev/console
PHPFILE="/etc/templates/radiusclient.php"
xmldbc -A $PHPFILE


