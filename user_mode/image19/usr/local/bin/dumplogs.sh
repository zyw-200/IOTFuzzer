#!/bin/sh
. /etc/generic_include.sh

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Manufacturing Data" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
printmd >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Date and Time" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
date >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Memory Usage" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
free >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
cat /proc/meminfo >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "CPU and Memory Usage" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
top -n 1 >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Current Running Processes" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
ps -A >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Current Status of Interfaces" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
ifconfig >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Current State of Wireless Interfaces" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
iwconfig >> /tmp/logs 2>>/dev/null

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "AP uptime" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
conf_get system:monitor:DeviceInfo:UpTime >> /tmp/logs
echo "" >> /tmp/logs

PID=`pidof wifidog | awk '{print $1}'` 
if [ $PID ]; then
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "IP Tables entries" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
iptables -t nat -L -nv >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Bridge Tables" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
brctl show >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "HTTP Redirect Database" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo 1 > /proc/sys/net/bridge/bridge-http-redirect-print-mac
echo 1 > /proc/sys/net/bridge/bridge-http-redirect-print-ip
brctl showmacs brvlan1 >> /tmp/logs
echo "" >> /tmp/logs
fi # $PID

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Packet statistics /proc/net/dev" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
cat /proc/net/dev >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Packet statistics /proc/net/wireless" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
cat /proc/net/wireless >> /tmp/logs
echo "" >> /tmp/logs

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Buffer statistics of VAP interfaces" >> /tmp/logs

for i in `seq 0 $(expr $NO_OF_RADIOS - 1)`; do
	for j in `seq 0 $(expr $NO_OF_VAPS - 1)`; do
		echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
		echo "WLAN Interface: wifi${i}vap${j}" >> /tmp/logs
		echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
		$WLANCONFIG wifi${i}vap${j} list wbuf >> /tmp/logs
		echo "" >> /tmp/logs
	done
done	

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Associated Station Details" >> /tmp/logs

for i in `seq 0 $(expr $NO_OF_RADIOS - 1)`; do
	for j in `seq 0 $(expr $NO_OF_VAPS - 1)`; do
		echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
		echo "WLAN Interface: wifi${i}vap${j}" >> /tmp/logs
		echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
		$WLANCONFIG wifi${i}vap${j} list sta >> /tmp/logs
		echo "" >> /tmp/logs
	done
done

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "WME Hardware Queues" >> /tmp/logs

for i in `seq 0 $(expr $NO_OF_RADIOS - 1)`; do
	for j in `seq 0 $(expr $NO_OF_VAPS - 1)`; do
		echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
		echo "WLAN Interface: wifi${i}vap${j}" >> /tmp/logs
		echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
		$WLANCONFIG wifi${i}vap${j} list hwq >> /tmp/logs
		echo "" >> /tmp/logs
	done
done		
echo "" >> /tmp/logs


if [ $PRODUCT_ID = "WNDAP660" ] || [ $PRODUCT_ID = "WNDAP620" ]; then
	echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
	echo "VAP Statistics" >> /tmp/logs
	for i in `seq 0 $(expr $NO_OF_RADIOS - 1)`; do
		for j in `seq 0 $(expr $NO_OF_VAPS - 1)`; do
			echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
			echo "WLAN Interface: wifi${i}vap${j}" >> /tmp/logs
			echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
			$WLANCONFIG wifi${i}vap${j} list get_vap_stats >> /tmp/logs
			echo "" >> /tmp/logs
		done
	done		
	echo "" >> /tmp/logs
fi # [ $PRODUCT_ID = "WNDAP660" ] || [ $PRODUCT_ID = "WNDAP620" ];

echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
echo "Current Kernel Logs" >> /tmp/logs
echo "-------------------------------------------------------------------------------------------------" >> /tmp/logs
dmesg >> /tmp/logs
echo "" >> /tmp/logs

cd /tmp
if [ $PRODUCT_ID = "WNDAP660" ]; then
	tar zcf logs.tar.gz logs \
	versions.txt \
	/var/config \
	/var/pal.cfg \
	/etc/resolv.conf \
	/var/log/messages.1 \
	/var/log/messages.0 \
	/var/log/messages \
	/etc/ap.conf.wifi0 \
	/etc/ap.conf.wifi1 \
	/etc/bss0.conf.wifi0 \
	/etc/bss1.conf.wifi0 \
	/etc/bss2.conf.wifi0 \
	/etc/bss3.conf.wifi0 \
	/etc/bss4.conf.wifi0 \
	/etc/bss5.conf.wifi0 \
	/etc/bss6.conf.wifi0 \
	/etc/bss7.conf.wifi0 \
	/etc/bss0.conf.wifi1 \
	/etc/bss1.conf.wifi1 \
	/etc/bss2.conf.wifi1 \
	/etc/bss3.conf.wifi1 \
	/etc/bss4.conf.wifi1 \
	/etc/bss5.conf.wifi1 \
	/etc/bss6.conf.wifi1 \
	/etc/bss7.conf.wifi1 > /dev/null 2>&1
elif [ $PRODUCT_ID = "WNDAP620" ]; then
	tar zcf logs.tar.gz logs \
	versions.txt \
	/var/config \
	/var/pal.cfg \
	/etc/resolv.conf \	
	/var/log/messages.1 \
	/var/log/messages.0 \
	/var/log/messages \
	/etc/ap.conf.wifi0 \
	/etc/bss0.conf.wifi0 \
	/etc/bss1.conf.wifi0 \
	/etc/bss2.conf.wifi0 \
	/etc/bss3.conf.wifi0 \
	/etc/bss4.conf.wifi0 \
	/etc/bss5.conf.wifi0 \
	/etc/bss6.conf.wifi0 \
	/etc/bss7.conf.wifi0 > /dev/null 2>&1
elif [ $PRODUCT_ID = "WNDAP350" ] || [ $PRODUCT_ID = "WNDAP360" ]; then	
	tar zcf logs.tar.gz logs \
	versions.txt \
	/var/config \
	/var/pal.cfg \
	/etc/resolv.conf \
	/var/log/messages.1 \
	/var/log/messages.0 \
	/var/log/messages \
	/etc/hostapd.conf.wifi0 \
	/etc/hostapd.conf.wifi1 > /dev/null 2>&1
else
	tar zcf logs.tar.gz logs \
	versions.txt \
	/var/config \
	/var/pal.cfg \
	/etc/resolv.conf \
	/var/log/messages.1 \
	/var/log/messages.0 \
	/var/log/messages \
	/etc/hostapd.conf.wifi0 > /dev/null 2>&1
fi # Starts @ Line 160
rm -rf logs
 
