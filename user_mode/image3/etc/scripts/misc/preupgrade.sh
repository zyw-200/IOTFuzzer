#!/bin/sh
echo [$0] $1 ... > /dev/console
if [ "$1" = "restore" ]; then
	echo RESTORE > /dev/console
	/etc/templates/upnpd.sh restart > /dev/console
else
	echo PREUPGRADE > /dev/console
	rgdb -s /firmware/update 1
	submit COMMIT
	/etc/templates/upnpd.sh stop > /dev/console
fi
