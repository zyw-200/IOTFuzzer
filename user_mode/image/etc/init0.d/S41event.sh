#!/bin/sh
echo [$0]: $1 ... > /dev/console
if [ "$1" = "start" ]; then
event SYSLOG_MSG add "sh /var/run/syslog_msg.sh"
fi
