#!/bin/sh
echo [$0] $1 $2 $3 ....

case "$1" in
NEW_CLIENT)
	logger -p notice -t WIFI "Got new client [$3] associated from WLAN-2 (5 Ghz)"
	;;
*)
	echo "not support [$1] ..."
	;;
esac
exit 0
