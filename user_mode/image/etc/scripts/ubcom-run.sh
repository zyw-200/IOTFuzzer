#!/bin/sh
echo [$0] $1... > /dev/console
case "$1" in
start)
	sh /etc/scripts/ubcom-monitor.sh &
	echo $! > /var/run/ubcom-monitor.pid
    ;;
stop)
    if [ -f /var/run/ubcom-monitor.pid ]; then
    	pid=`cat /var/run/ubcom-monitor.pid`
    	if [ $pid != 0 ]; then
		    kill $pid > /dev/null 2>&1
		fi
		rm -f /var/run/ubcom-monitor.pid
  	fi
	killall -9 ubcom
	;;
restart)
	$0 stop
	$0 start
	;;
*)
	echo "usage: ubcom-run.sh {start|stop|restart}"
	;;
esac
