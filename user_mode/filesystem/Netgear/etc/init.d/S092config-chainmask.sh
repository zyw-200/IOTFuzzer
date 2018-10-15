#!/bin/sh

if [ ${PLATFORM} ]; then
	if [ ${NO_OF_RADIOS} -eq 2 ]; then
		(/bin/sh /usr/local/bin/config-chainmask.sh >/dev/null 2>&1) &
	fi
fi
