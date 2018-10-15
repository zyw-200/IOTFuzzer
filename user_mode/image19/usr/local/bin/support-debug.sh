#!/bin/sh
if [ $# -eq 0 ]; then 
	echo "usgae: $0 disable | enable | upload"
	exit 1
fi

if [ -z $2 ]; then
	ATH_FLAGS=0x80000020
else
	ATH_FLAGS=$2
fi
	
if [ -z $3 ]; then
	VAP_DEV=wifi0vap0
else
	VAP_DEV=$3
fi

if [ -z $4 ]; then
	VAP_FLAGS=0x00c00000
else
	VAP_FLAGS=$4
fi

if [ $1 == "enable" ]; then

	echo "ATH_FLAGS = $ATH_FLAGS, VAP_DEV = $VAP_DEV, VAP_FLAGS = $VAP_FLAGS"
	echo ""

	/usr/local/bin/athdebug -i wifi0 $ATH_FLAGS

	/usr/local/bin/80211debug -i $VAP_DEV $VAP_FLAGS

	killall hostapd

	echo "running 0 iteration @ `date`" > /tmp/hostapd_debug.log
	/usr/local/bin/hostapd -dd /etc/hostapd.conf.wifi0 >> /tmp/hostapd_debug.log 2>&1 &
	echo "running ${i} iteration @ `date`" > /tmp/estats.log
	/usr/local/bin/wlanconfig $VAP_DEV list buffer 0 >> /tmp/estats.log 
	/usr/local/bin/wlanconfig $VAP_DEV list wbuf 0 >> /tmp/estats.log 
	/usr/local/bin/wlanconfig $VAP_DEV list hwq 0 >> /tmp/estats.log 
	/usr/local/bin/wlanconfig $VAP_DEV list sta 0 >> /tmp/estats.log 
	/usr/local/bin/wlanconfig $VAP_DEV list estats 0 >> /tmp/estats.log 
	sleep 2
	for i in `seq 1 4`;do
		echo "##########################################################" >> /tmp/estats.log
		echo "running ${i} iteration @ `date`" >> /tmp/estats.log
		/usr/local/bin/wlanconfig $VAP_DEV list buffer 0 >> /tmp/estats.log 
		/usr/local/bin/wlanconfig $VAP_DEV list wbuf 0 >> /tmp/estats.log 
		/usr/local/bin/wlanconfig $VAP_DEV list hwq 0 >> /tmp/estats.log 
		/usr/local/bin/wlanconfig $VAP_DEV list sta 0 >> /tmp/estats.log 
		/usr/local/bin/wlanconfig $VAP_DEV list estats 0 >> /tmp/estats.log
		sleep 2
	done

elif [ $1 == "disable" ]; then

	/usr/local/bin/athdebug -i wifi0 0

	/usr/local/bin/80211debug -i wifi0vap0 0
	/usr/local/bin/80211debug -i wifi0vap1 0
	/usr/local/bin/80211debug -i wifi0vap2 0
	/usr/local/bin/80211debug -i wifi0vap3 0
	/usr/local/bin/80211debug -i wifi0vap4 0
	/usr/local/bin/80211debug -i wifi0vap5 0
	/usr/local/bin/80211debug -i wifi0vap6 0
	/usr/local/bin/80211debug -i wifi0vap7 0

	/usr/local/bin/80211debug -i wifi0wds0 0
	/usr/local/bin/80211debug -i wifi0wds1 0
	/usr/local/bin/80211debug -i wifi0wds2 0
	/usr/local/bin/80211debug -i wifi0wds3 0

	killall hostapd
	/usr/local/bin/hostapd /etc/hostapd.conf.wifi0 &
        
	echo "##########################################################" >> /tmp/estats.log
	echo "running during disable command @ `date`" >> /tmp/estats.log
	/usr/local/bin/wlanconfig $VAP_DEV list buffer 0 >> /tmp/estats.log 
	/usr/local/bin/wlanconfig $VAP_DEV list wbuf 0 >> /tmp/estats.log 
	/usr/local/bin/wlanconfig $VAP_DEV list hwq 0 >> /tmp/estats.log 
	/usr/local/bin/wlanconfig $VAP_DEV list sta 0 >> /tmp/estats.log 
	/usr/local/bin/wlanconfig $VAP_DEV list estats 0 >> /tmp/estats.log
	sleep 2
	echo "running 0 iteration @ `date`" > /tmp/dmesg_log.log
	dmesg >> /tmp/dmesg_log.log
elif [ $1 == "upload" ]; then
     cd /tmp     
     tftp -p -l hostapd_debug.log $2
     tftp -p -l estats.log $2
     tftp -p -l dmesg_log.log $2
fi
exit 0
