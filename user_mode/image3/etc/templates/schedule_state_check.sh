#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
if [ "`rgdb -i -g /wlan/inf:1/multi/index:0/schedule_state`" = "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /wlan/inf:1/multi/index:1/schedule_state`" = "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /wlan/inf:1/multi/index:2/schedule_state`" = "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /wlan/inf:1/multi/index:3/schedule_state`" = "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /wlan/inf:1/multi/index:4/schedule_state`" = "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /wlan/inf:1/multi/index:5/schedule_state`" = "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /wlan/inf:1/multi/index:6/schedule_state`" = "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /wlan/inf:1/multi/index:7/schedule_state`" = "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /wlan/inf:1/multi/index:0/schedule_rule_state`" != "1" ]; then
	rgdb -i -s /schedule/wlan_restart 1
fi
if [ "`rgdb -i -g /schedule/wlan_restart`" = "1" ]; then
	service WLAN restart
	rgdb -i -s /schedule/wlan_restart 0
else
	service WLAN stop
fi

