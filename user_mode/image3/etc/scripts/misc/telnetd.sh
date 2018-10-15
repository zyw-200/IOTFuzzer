#!/bin/sh
image_sign=`cat /etc/config/image_sign`
TELNETD=`rgdb -g /sys/telnetd`
PROTOCOL=`rgdb -g /sys/consoleprotocol/protocol`
TIMEOUT=`rgdb -g /sys/consoleprotocol/timeout`
if [ "$TELNETD" = "true" -o "$PROTOCOL" = "1" ]; then
	echo "Start telnetd ..." > /dev/console
	if [ -f "/usr/sbin/login" ]; then
		lf=`rgdb -i -g /runtime/layout/lanif`
		if [ ! -z "$TIMEOUT" ]; then
			to="-t $TIMEOUT"
		else
			to=
		fi
		#modified by yuejun,if the password contains '\' character,you can't login the system from telnet,
		#becase the password has been changed by shell. 
		#username=`rgdb -g /sys/user:1/name`
		#password=`rgdb -g /sys/user:1/password`
		#telnetd -l "/usr/sbin/login" -u $username:$password -i $lf -s "-l/usr/sbin/cli" -t 0 $to &
		telnetd -l "/usr/sbin/login" -u `rgdb -g /sys/user:1/name`:`rgdb -g /sys/user:1/password` -i $lf -s "-l/usr/sbin/cli" -t 0 $to &
		echo "$!" > /var/run/telnetd.pid
	else
		telnetd &
	fi
else
	echo "Disable start-up daemon: telnetd."
	exit 0
fi
