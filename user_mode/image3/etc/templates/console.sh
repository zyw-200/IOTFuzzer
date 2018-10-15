#!/bin/sh
echo [$0] setting ... > /dev/console
CON=`rgdb -g /sys/consoleprotocol/protocol`

		echo -n "Stopping telnetd ... "
		if test -r /var/run/telnetd.pid; then
			kill `cat /var/run/telnetd.pid` 2> /dev/null && rm -f /var/run/telnetd.pid || echo " failed."
		else
			echo " no PID."
		fi

		/etc/templates/sshd.sh stop

case "$CON" in
	0)
		echo "None console protocol!"
		;;
	1)
		/etc/scripts/misc/telnetd.sh
		;;
	2)
		/etc/templates/sshd.sh start
		;;
	*)
		echo "wow"
		;;
esac

