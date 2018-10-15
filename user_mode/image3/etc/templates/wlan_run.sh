#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)

    
if [ "`rgdb -i -g /runtime/wireless/setting/status`" = "0" ]; then
	sh /etc/templates/autorekey.sh start &> /dev/console # log add autorekey    
	sleep 1   
	
	[ -f /var/run/wlan_stop.sh ] && sh /var/run/wlan_stop.sh > /dev/console
	
	echo "start WLAN ....."     > /dev/console
	rgdb -A $TROOT/wlan_run.php -V generate_start=0 > /var/run/wlan_stop.sh
	rgdb -A $TROOT/wlan_run.php -V generate_start=1 > /var/run/wlan_start.sh
	rgdb -A $TROOT/multi_ssid_run_g.php -V generate_start=0 > /var/run/multi_ssid_stop_g.sh   
#	rgdb -A $TROOT/multi_ssid_run_a.php -V generate_start=0 > /var/run/multi_ssid_stop_a.sh
	rgdb -A $TROOT/wlan_insmod.php > /var/run/wlan_insmod.sh	# joe, leave insert module alone
	rgdb -A $TROOT/__wlan_device_up.php > /var/run/wlan_device_up.sh
	
if [ "`rgdb -i -g /runtime/nvram/mfc_phase`" = "" ] || [ "`rgdb -i -g /runtime/nvram/mfc_phase`" = "0" ] || [ "`rgdb -i -g /runtime/mfc/testmode`" = "1" ]; then
       rgdb -i -s /runtime/wireless/setting/status 1

	[ -f /etc/templates/certs/certscmd.sh ] && sh /etc/templates/certs/certscmd.sh GET_CERT > /dev/console
	
	sh /var/run/wlan_insmod.sh > /dev/console # joe, leave insert module alone
	# joe, check wifi0 and wifi1 PHY before wlan start
	if [ "`ifconfig wifi0 | grep wifi0 | cut -b 0-5`" = "wifi0" ]; then 
#		if [ "`ifconfig wifi1 | grep wifi1 | cut -b 0-5`" = "wifi1" ]; then 
			sh /var/run/wlan_start.sh > /dev/console #joe, make VAP, including MSSID
			echo "sleep 2....."     > /dev/console	
			sleep 2

	        
			sh /var/run/wlan_device_up.sh > /dev/console #joe, activate VAP finally
			rgdb -A $TROOT/__auth_topology.php -V generate_start=1 > /var/run/auth_topology_start.sh # log_luo add for new hostapd and wpa_supplicant
			rgdb -A $TROOT/__auth_topology.php -V generate_start=0 > /var/run/auth_topology_stop.sh # log_luo add for new hostapd and wpa_supplicant
			sh  /var/run/auth_topology_start.sh &> /dev/console
#		fi
	fi
	rgdb -i -s /rumtime/wlan/inf:1/autorekey/first 0
#	rgdb -i -s /rumtime/wlan/inf:2/autorekey/first 0
	rgdb -i -s /runtime/wireless/setting/status 0
	if [ "`rgdb -i -g /wlan/inf:1/ap_mode`" = "0" ]; then
                sh /etc/templates/lld2d.sh restart > /dev/console
        else
                sh /etc/templates/lld2d.sh stop  > /dev/console
        fi

        rgdb -A $TROOT/__vlan.php -V generate_start=1 > /var/run/vlan_start.sh  # jack add multi_ssid 09/03/07
        rgdb -A $TROOT/__vlan.php -V generate_start=0 > /var/run/vlan_stop.sh   # jack add multi_ssid 09/03/07
        sh /var/run/vlan_stop.sh > /dev/console
        sh /var/run/vlan_start.sh > /dev/console
        #start vlan for eth0 port
        rgdb -A /etc/scripts/eth0_vlan.php -V generate_start=1 > /var/run/eth0_vlan_start.sh
        rgdb -A /etc/scripts/eth0_vlan.php -V generate_start=0 > /var/run/eth0_vlan_stop.sh
        sh /var/run/eth0_vlan_stop.sh
        sh /var/run/eth0_vlan_start.sh

	submit CAPTIVAL_PORTAL # restart captival portal daemon
	submit LOADBALANCE	#restart loadbalance for restart wlan
    submit QOS_TC_TM;  # restart qos and traffic manager module;
    submit NETFILTER;
	submit ARP_SPOOFING > /dev/null  # eric fu , arp spoofing disable in apc mode.
	CheckFreeMEM & > /dev/console
	
	echo "sleep 5....."     > /dev/console
	sleep 5
	sh /var/run/wlan_servd_start.sh
fi
fi
	;;
stop)
	if [ "`rgdb -i -g /runtime/wireless/setting/status`" = "0" ]; then
		rgdb -i -s /runtime/wireless/setting/status 1 

    #STOP QOS
	submit QOS_TC_TM_STOP;

	sh /var/run/autorekey_stop.sh > /dev/console  # log add autorekey

	if [ -f /var/run/vlan_stop.sh ]; then 
		sh /var/run/vlan_stop.sh > /dev/console
	fi
	if [ -f /var/run/auth_topology_stop.sh ]; then
		sh /var/run/auth_topology_stop.sh > /dev/console
		rm -f var/run/auth_topology_stop.sh
	fi
	if [ -f /var/run/multi_ssid_stop_g.sh ]; then 
		sh /var/run/multi_ssid_stop_g.sh > /dev/console
		rm -f /var/run/multi_ssid_stop_g.sh
	fi
	if [ -f /var/run/multi_ssid_stop_a.sh ]; then 
		sh /var/run/multi_ssid_stop_a.sh > /dev/console
		rm -f /var/run/multi_ssid_stop_a.sh
	fi
	
	if [ -f /var/run/wlan_stop.sh ]; then
		sh /var/run/wlan_stop.sh > /dev/console
		rm -f /var/run/wlan_stop.sh
	fi
	rgdb -i -s /runtime/wireless/setting/status 0
	fi	
	;;
*)
	echo "usage: wlan.sh {start|stop|restart}" > /dev/console
#	echo "       if option "a" or "g" are not give," > /dev/console
	echo "       both wlan would be start|stop|restart." > /dev/console
	;;
esac
