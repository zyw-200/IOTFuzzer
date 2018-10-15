#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
rgdb -A $TROOT/wan_down.php > /var/run/wan_down.sh
sh /var/run/wan_down.sh > /dev/console
