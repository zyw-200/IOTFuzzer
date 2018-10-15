#!/bin/sh
#
# Does all the maufacturing data related stuff.
#

# bddatard returns non zero if mac address is invalid
[ ! -e /var/mfdata ] && ${WR_MFG_DATA}

if [ ! -z ${BDDATARD} ]; then
	if [ ! -z ${WR_MFG_DATA} ]; then
		ncecho 'Checking Manufac. data      '
		${BDDATARD} ${MANUFAC_MTD_CHAR} 0 > ${NULL_DEVICE}
		if [ $? -ne 0 ]; then
			if [ ! -z ${JP_TERRI} ]; then
				ncecho 'Japan territory Selected by default.        '
				${WR_MFG_DATA} -m 001122334455 -c 2
			else
				cecho yellow '[DEFAULT]'
				${WR_MFG_DATA} -m 001122334455 -c 0
			fi
		else
			cecho green '[DONE]'
		fi
	fi
fi

if [ ! -z ${PRINTMD} ]; then
        ncecho 'Checking board file.        '
	if [ ! -e ${MANU_BOARD_FILE} ]; then
		cecho yellow '[CREATED]'
		${PRINTMD} ${MANUFAC_MTD_CHAR} > ${MANU_BOARD_FILE}
	else
		cecho green '[DONE]'
	fi
fi
