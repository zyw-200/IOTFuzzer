#!/bin/sh
#
# All ethernet related stuff should come here, from inserting modules upto setti
#
ncecho 'Loading Ethernet module.    '

if [ ! ${PLATFORM} ]; then
MODULE_TO_INSERT=`find /lib/modules -name ${ETH_DRIVER}`

if [ -e ${MODULE_TO_INSERT} ]; then

        if [ ${CONFIG_TX_LEN} = "yes" ]; then
                ${INSMOD} ${MODULE_TO_INSERT}
        else
                ${INSMOD} ${MODULE_TO_INSERT} tx_len_per_ds=512 fifo_3=0
        fi
        if [  $? != 0 ]; then
        	cecho red '[FAILED]'
                exit;
        fi
	if [ ${ETH_MAC_CONGIURABLE} = "yes" ]; then
		if [ -e ${MANU_BOARD_FILE} ]; then
			cecho yellow '[GENMAC]'
			ncecho '                            '
			if [ -e ${BDDATARD} ]; then
	        	${BDDATARD} ${MAC_OFFSET_4_ETH} | xargs ${IFCONFIG} ${ETH_INTERFACE} hw ether
			fi
		fi
		${IFCONFIG} ${ETH_INTERFACE} mtu ${ETH_MTU}
	fi

        cecho green '[DONE]'
else
        cecho red '[FAILED]'
fi
else
	ip address flush dev ${ETH_INTERFACE}
	${BDDATARD} ${MANUFAC_MTD_CHAR} ${MAC_OFFSET_4_ETH} | xargs ${IFCONFIG} ${ETH_INTERFACE} down hw ether
	${IFCONFIG} ${ETH_INTERFACE} up
	${IFCONFIG} ${ETH_INTERFACE} mtu ${ETH_MTU}
	cecho green '[DONE]'
fi
