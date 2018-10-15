#!/bin/sh
echo [$0]: $1 ... > /dev/console
if [ "$1" = "start" ]; then
	event "STATUS.READY"		add "usockc /var/gpio_ctrl STATUS_GREEN"
	event "STATUS.CRITICAL"		add "usockc /var/gpio_ctrl STATUS_AMBER_BLINK"
	event "STATUS.NOTREADY"		add "usockc /var/gpio_ctrl STATUS_AMBER"
	event "STATUS.GREEN"		add "usockc /var/gpio_ctrl STATUS_GREEN"
	event "STATUS.GREEBBLINK"	add "usockc /var/gpio_ctrl STATUS_GREEN_BLINK"
	event "STATUS.AMBER"		add "usockc /var/gpio_ctrl STATUS_AMBER"
	event "STATUS.AMBERBLINK"	add "usockc /var/gpio_ctrl STATUS_AMBER_BLINK"
	event "WAN-1.CONNECTED"		add "usockc /var/gpio_ctrl INET_GREEN"
	event "WAN-2.CONNECTED"		add "null"
	event "WAN-1.DISCONNECTED"	add "usockc /var/gpio_ctrl INET_AMBER"
	event "WAN-2.DISCONNECTED"	add "null"
	event "WPS.INPROGRESS"		add "usockc /var/gpio_ctrl WPS_IN_PROGRESS"
	event "WPS.SUCCESS"			add "usockc /var/gpio_ctrl WPS_SUCCESS"
	event "WPS.OVERLAP"			add "usockc /var/gpio_ctrl WPS_OVERLAP"
	event "WPS.ERROR"			add "usockc /var/gpio_ctrl WPS_ERROR"
	event "WPS.NONE"			add "usockc /var/gpio_ctrl WPS_NONE"
	event "QUICKCONFIG.IODATA"	add "/etc/scripts/quickgamestart.sh start"
fi
