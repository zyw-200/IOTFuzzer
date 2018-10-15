#!/bin/sh

vap_and_wds()
{
		PRODUCT_ID=`grep ProductID /etc/board | cut -d ' ' -f 3`

		ncecho 'Creating vap interface.     '

		for i in `seq 0 $(expr $NO_OF_RADIOS - 1)`;do
			for j in `seq 0 $(expr $NO_OF_VAPS - 1)`; do
				${WLANCONFIG} wifi${i}vap$j create wlandev wifi${i} wlanmode ap > ${NULL_DEVICE} 2>&1
				if [ ${j} = 0 ]; then
					${IFCONFIG} wifi${i}vap$j txqueuelen 200 > ${NULL_DEVICE} 2>&1
				else	
					${IFCONFIG} wifi${i}vap$j txqueuelen 50 > ${NULL_DEVICE} 2>&1
				fi
			done
		done
		cecho green '[DONE]'


		ncecho 'Creating wds interface.     '

		if [ ${PRODUCT_ID} = "WNDAP660" ] || [ ${PRODUCT_ID} = "WNDAP620" ] || [ ${PRODUCT_ID} = "wndap660" ] || [ ${PRODUCT_ID} = "wndap620" ]
		then
			for i in `seq 0 $(expr $NO_OF_RADIOS - 1)`;do
				for j in `seq 0 $(expr $NO_OF_WDS - 1)`; do
					${WLANCONFIG} wifi${i}wds$j create wlandev wifi${i} wlanmode ap > ${NULL_DEVICE} 2>&1
					${WLANCONFIG} wifi${i}wds$j nawds mode 2 > ${NULL_DEVICE} 2>&1
					${IFCONFIG} wifi${i}wds$j mtu 1504 txqueuelen 50 > ${NULL_DEVICE} 2>&1
					/sbin/iwpriv wifi${i}wds$j wds 1 > ${NULL_DEVICE} 2>&1
				done
			done		
		else
	                for i in `seq 0 $(expr $NO_OF_RADIOS - 1)`;do
        	                for j in `seq 0 $(expr $NO_OF_WDS - 1)`; do
                	                ${WLANCONFIG} wifi${i}wds$j create wlandev wifi${i} wlanmode swds > ${NULL_DEVICE} 2>&1
                        	        ${IFCONFIG} wifi${i}wds$j mtu 1504 txqueuelen 50 > ${NULL_DEVICE} 2>&1
	                        done
        	        done
		fi

		cecho green '[DONE]'
}

client_mode()
{
                ncecho 'Creating sta interface.     '
                ${RMMOD} ath_${WLAN_BUS}
                ${RMMOD} bridge.ko
                ${INSMOD} /lib/modules/wlan/ath_${WLAN_BUS}.ko
                ${INSMOD} /lib/modules/net/client_bridge/client_bridge.ko
                ${WLANCONFIG} wifi0vap0 create wlandev wifi0 wlanmode sta > ${NULL_DEVICE} 2>&1
#               ${IFCONFIG} wifi0vap0 txqueuelen 200
                ${IFCONFIG} wifi0vap0 txqueuelen 1000 > ${NULL_DEVICE} 2>&1
                cecho green '[DONE]'
}

[ -x ${IFCONFIG} ] && [ -x ${WLANCONFIG} ] || exit

AP_MODE=`grep wlan0:apMode /var/config | cut -d ':' -f 5 | cut -d ' ' -f 2`

if [ ${CLIENT_MODE} = "yes" ]; then
	if [ ${AP_MODE} = "5" ]; then
		client_mode
	else
		vap_and_wds
	fi
else
	vap_and_wds
fi
