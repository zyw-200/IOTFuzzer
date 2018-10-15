#!/bin/sh

# First argument is driver, second is user space pgm.
system_module_load()
{
	[ -f $1 ] && [ -f $2 ] || exit 0
	${INSMOD} $1
	if [ $? != 0 ]; then 
		cecho red '[FAILED]'
	else
		cecho green '[DONE]'
	fi
	$2 $3&
}

if [ ${START_LED_DRIVER} = "yes" ]; then
	MODULE_TO_INSERT=`find /lib/modules -name ${LED_DRIVER}`
        ncecho 'Starting Panel LED.         '
	system_module_load ${MODULE_TO_INSERT} ${PANEL_LED} ${LED_BOOTING_MODE}
fi

if [ ${START_WDT_DRIVER} = "yes" ]; then
	MODULE_TO_INSERT=`find /lib/modules -name ${WDT_DRIVER}`
        ncecho 'Starting watchdog.          '
	system_module_load ${MODULE_TO_INSERT} ${SYS_WATCHDOG}
fi

if [ ${START_RST_DRIVER} = "yes" ]; then
	MODULE_TO_INSERT=`find /lib/modules -name ${PANEL_RST_DRIVER}`
        ncecho 'Starting Reset Detect.      '
	system_module_load ${MODULE_TO_INSERT} ${PANEL_RESET}
fi
