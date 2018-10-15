#!/bin/sh
#echo [$0] $1 $2....
RSLT="/var/ping_result"
case "$1" in
set)
	rm -f $RSLT
	ping $2 > $RSLT
	;;
get)
	if [ -f $RSLT ]; then
		cat $RSLT
	fi
	;;
esac
exit 0
