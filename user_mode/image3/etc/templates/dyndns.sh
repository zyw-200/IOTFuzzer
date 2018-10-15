#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
START=1
[ "$1" = "stop" ] && START=0
rgdb -A $TROOT/misc/dyndns_run.php -V generate_start=$START > /var/run/dyndns.sh
sh /var/run/dyndns.sh > /dev/console
