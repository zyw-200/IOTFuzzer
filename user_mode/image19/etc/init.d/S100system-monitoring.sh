#!/bin/sh

ncecho 'Starting SysMon Script...   '
/usr/local/bin/sysmonitor.sh > /dev/null 2>&1 &
cecho green '[DONE]'
sleep 1

