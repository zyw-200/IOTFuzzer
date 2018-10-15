#!/bin/sh
echo [$0] ... > /dev/console
if [ $1 = "g" ]; then
APMODE=`rgdb -g /wlan/inf:1/ap_mode`
CONNECT=`rgdb -i -g /runtime/stats/wlan/inf:1/client:1/mac`
ATH_DEV="ath0"
else
APMODE=`rgdb -g /wlan/inf:2/ap_mode`
CONNECT=`rgdb -i -g /runtime/stats/wlan/inf:2/client:1/mac`
ATH_DEV="ath16"

fi

if [ "$APMODE" = "1" ]; then
	if [ -z "$CONNECT" ]; then
			wlanconfig $ATH_DEV list scan
	else
	iwlist $ATH_DEV scanning apclient_scan
	fi
else 
iwlist $ATH_DEV scanning apclient_scan
fi
