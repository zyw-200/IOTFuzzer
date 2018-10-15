#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
xmldbc -A $TROOT/misc/onlanchange.php > /var/run/olc.sh
sh /var/run/olc.sh
