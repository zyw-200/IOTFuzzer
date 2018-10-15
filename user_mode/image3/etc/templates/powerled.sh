#!/bin/sh
echo [$0] ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/powerled_run.php ] && exit 0
xmldbc -A $TROOT/powerled_run.php  > /var/run/powerled_run.sh
sh /var/run/powerled_run.sh > /dev/console
