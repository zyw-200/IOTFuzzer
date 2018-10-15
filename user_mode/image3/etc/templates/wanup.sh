#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
rgdb -A $TROOT/wan_up.php > /var/run/wan_up.sh
sh /var/run/wan_up.sh > /dev/console
