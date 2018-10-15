#!/bin/sh
echo "[$0] ...." > /dev/console
service WAN stop
event STATUS.CRITICAL
sleep 3
event HTTP.DOWN add /etc/events/FWUPDATER.sh
service HTTP stop
exit 0
