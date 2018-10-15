#!/bin/sh
echo "ubom monitor start...."
xmldbc -s /runtime/device/qos/monitor 1
TRY_COUNT=0

while [ 1 = 1 ]; do
	UBCOM=`xmldbc -W /device/qos/autobandwidth`
	if [ "$UBCOM" = 1 ]; then
		BU=`xmldbc -W /runtime/device/qos/bwup`
		if [ "$BU" = "" ] || [ "$BU" = 0 ]; then
			echo "Ubcom start[$TRY_COUNT]..."
			killall -9 ubcom
			ubcom 
			if [ $TRY_COUNT = 3 ]; then
				echo "Try more than three, stop to detect..."
				xmldbc -s /runtime/device/qos/monitor 0
				exit 0
			else
				echo $TRY_COUNT
				if [ "$TRY_COUNT" = 0 ];then 
					TRY_COUNT=1
				elif [ "$TRY_COUNT" = 1 ];then
					TRY_COUNT=2
				elif [ "$TRY_COUNT" = 2 ];then
					TRY_COUNT=3 
				fi
			fi
		else
			echo "Already got bandwidth..."
			xmldbc -s /runtime/device/qos/monitor 0 
			exit 0
		fi
	else
		echo "Ubcom function is closed..."
		xmldbc -s /runtime/device/qos/monitor 0
		exit 0
	fi
done
