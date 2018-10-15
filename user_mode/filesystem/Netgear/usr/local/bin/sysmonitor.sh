#!/bin/sh
. /etc/generic_include.sh

CONFIGD_TIMEOUT=1
CONFIGD_TIMER=0

SYSLOG_TIMEOUT=5
SYSLOG_TIMER=0

SNMPD_TIMEOUT=5
SNMPD_TIMER=0

WD_TIMEOUT=6
WD_TIMER=0

WSUP_TIMEOUT=4
WSUP_TIMER=0

HAPD_TIMEOUT=4
HAPD_TIMER=0

NMBD_TIMEOUT=25
NMBD_TIMER=0

GCD_TIMEOUT=5
GCD_TIMER=0

FREE_MEM_THRESHOLD=5000

STOPPED_PROC_COUNT=0

while true
do
	FREE_MEM=`/usr/bin/free | grep Total | /usr/bin/awk '{print $4}'`
	if [ $FREE_MEM -lt $FREE_MEM_THRESHOLD ]; then
		logger -s "Sytem Memory less than $FREE_MEM_THRESHOLD! Restarting system from monitoring script"
		reboot
	fi

	if [ $STOPPED_PROC_COUNT -ge 2 ]; then
		logger -s " 2 or more processes not running! Restarting system from monitoring script" 
		reboot
	fi

	AP_MODE=`grep wlan0:apMode /var/config | cut -d ':' -f 5 | cut -d ' ' -f 2`

	if [ ${AP_MODE} = "5" ]; then

		PID=`/bin/pidof configd | /usr/bin/awk '{print $1}'`
		if [ ! $PID ] && [ $CONFIGD_TIMER -ge $CONFIGD_TIMEOUT ]; then
			logger -s "configd stopped! restarting it from system monitoring script"
			start-stop-daemon -S -b -q --exec ${CONFIGD}
			STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
			continue
		elif [ ! $PID ] && [ $CONFIGD_TIMER -lt $CONFIGD_TIMEOUT ]; then
			CONFIGD_TIMER=`expr $CONFIGD_TIMER + 1`
		elif [ $PID ]; then
			CONFIGD_TIMER=0
		fi

		SYSLOGD=`/bin/grep syslogStatus /var/config | /usr/bin/awk '{print $2}'`
		if [ $SYSLOGD = "1" ]; then
			PID=`/bin/pidof syslogd | /usr/bin/awk '{print $1}'`
			if [ ! $PID ] && [ $SYSLOG_TIMER -ge $SYSLOG_TIMEOUT ]; then
				logger -s "Syslog client stopped! restarting it from system monitoring script"
				${TRANSLATORS_BIN_LOCATION}/syslog < /var/config > ${NULL_DEVICE} 2>&1
				STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
				continue
			elif [ ! $PID ] && [ $SYSLOG_TIMER -lt $SYSLOG_TIMEOUT ]; then
				SYSLOG_TIMER=`expr $SYSLOG_TIMER + 1`
			elif [ $PID ]; then
				SYSLOG_TIMER=0
			fi
		fi

		SNMPD=`/bin/grep snmpStatus /var/config | /usr/bin/awk '{print $2}'`
		if [ $SNMPD = "1" ]; then
			PID=`/bin/pidof snmpd | /usr/bin/awk '{print $1}'`
			if [ ! $PID ] && [ $SNMPD_TIMER -ge $SNMPD_TIMEOUT ]; then
				logger -s "snmpd stopped! restarting it from system monitoring script"
				${TRANSLATORS_BIN_LOCATION}/snmp < /var/config > ${NULL_DEVICE} 2>&1
				STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
				continue
			elif [ ! $PID ] && [ $SNMPD_TIMER -lt $SNMPD_TIMEOUT ]; then
				SNMPD_TIMER=`expr $SNMPD_TIMER + 1`
			elif [ $PID ]; then
				SNMPD_TIMER=0
			fi
		fi
		
		PID=`/bin/pidof wpa_supplicant | /usr/bin/awk '{print $1}'`
		if [ ! $PID ] && [ $WSUP_TIMER -ge $WSUP_TIMEOUT ]; then
			logger -s "wpa_supplicant stopped! restarting it from system monitoring script" 
			${TRANSLATORS_BIN_LOCATION}/client_bridge_tr < /var/config > ${NULL_DEVICE} 2>&1
			STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
			continue
		elif [ ! $PID ] && [ $WSUP_TIMER -lt $WSUP_TIMEOUT ]; then
			WSUP_TIMER=`expr $WSUP_TIMER + 1`
		elif [ $PID ]; then
			WSUP_TIMER=0
		fi
		
		PID=`/bin/pidof nmbd | /usr/bin/awk '{print $1}'`
		if [ ! $PID ] && [ $NMBD_TIMER -ge $NMBD_TIMEOUT ]; then
			logger -s "nmbd stopped! restarting it from system monitoring script"
			${TRANSLATORS_BIN_LOCATION}/nmbd_tr < /var/config > ${NULL_DEVICE} 2>&1
			STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
			continue
		elif [ ! $PID ] && [ $NMBD_TIMER -lt $NMBD_TIMEOUT ]; then
			NMBD_TIMER=`expr $NMBD_TIMER + 1`
		elif [ $PID ]; then
			NMBD_TIMER=0
		fi

		GCD=`/bin/grep cloudStatus /var/config | /usr/bin/awk '{print $2}'`
		if [ $GCD = "1" ]; then
			PID=`/bin/pidof pal.netgear | /usr/bin/awk '{print $1}'`
			if [ ! $PID ] && [ $GCD_TIMER -ge $GCD_TIMEOUT ]; then
				logger -s "pal.netgear stopped! restarting it from system monitoring script" 
				${TRANSLATORS_BIN_LOCATION}/pal_translator < /var/config > ${NULL_DEVICE} 2>&1
				STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
				# sleeping of an one second is mandatory - to avoid corruption of /var/pal.cfg
				sleep 1
				continue
			elif [ ! $PID ] && [ $GCD_TIMER -lt $GCD_TIMEOUT ]; then
				GCD_TIMER=`expr $GCD_TIMER + 1`
			elif [ $PID ]; then
				GCD_TIMER=0
			fi
		fi
	else

		PID=`/bin/pidof configd | /usr/bin/awk '{print $1}'`
		if [ ! $PID ] && [ $CONFIGD_TIMER -ge $CONFIGD_TIMEOUT ]; then
			logger -s "configd stopped! restarting it from system monitoring script" 
			/sbin/start-stop-daemon -S -b -q --exec ${CONFIGD}
			STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
			continue
		elif [ ! $PID ] && [ $CONFIGD_TIMER -lt $CONFIGD_TIMEOUT ]; then
			CONFIGD_TIMER=`expr $CONFIGD_TIMER + 1`
		elif [ $PID ]; then
			CONFIGD_TIMER=0
		fi

		SYSLOGD=`/bin/grep syslogStatus /var/config | /usr/bin/awk '{print $2}'`
		if [ $SYSLOGD = "1" ]; then
			PID=`/bin/pidof syslogd | /usr/bin/awk '{print $1}'`
			if [ ! $PID ] && [ $SYSLOG_TIMER -ge $SYSLOG_TIMEOUT ]; then
				logger -s "Syslog client stopped! restarting it from system monitoring script" 
				${TRANSLATORS_BIN_LOCATION}/syslog < /var/config > ${NULL_DEVICE} 2>&1
				STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
				continue
			elif [ ! $PID ] && [ $SYSLOG_TIMER -lt $SYSLOG_TIMEOUT ]; then
				SYSLOG_TIMER=`expr $SYSLOG_TIMER + 1`
			elif [ $PID ]; then
				SYSLOG_TIMER=0
			fi
		fi

		SNMPD=`/bin/grep snmpStatus /var/config | /usr/bin/awk '{print $2}'`
		if [ ${SNMPD} = "1" ]; then
			PID=`/bin/pidof snmpd | /usr/bin/awk '{print $1}'`
			if [ ! $PID ] && [ $SNMPD_TIMER -ge $SNMPD_TIMEOUT ]; then
				logger -s "snmpd stopped! restarting it from system monitoring script" 
				${TRANSLATORS_BIN_LOCATION}/snmp < /var/config > ${NULL_DEVICE} 2>&1
				STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
				continue
			elif [ ! $PID ] && [ $SNMPD_TIMER -lt $SNMPD_TIMEOUT ]; then
				SNMPD_TIMER=`expr $SNMPD_TIMER + 1`
			elif [ $PID ]; then
				SNMPD_TIMER=0
			fi
		fi

		CLD_CONNECTIVITY_STATUS=`/bin/grep cloudConnectivityStatus /var/config | /usr/bin/awk '{print $2}'`
		if [ $GCD = "1" ] && [ $CLD_CONNECTIVITY_STATUS = "1" ]; then
			for i in `seq 0 $(expr $NO_OF_RADIOS - 1)`;do
                        	for j in `seq 0 $(expr $NO_OF_VAPS - 1)`; do
					WIFIDOG=`/bin/grep wlan${i}:vap${j}:cpStatus /var/config | /usr/bin/awk '{print $2}'`
                                	if [ "$WIFIDOG" = "1" ]; then
						break;
                                	fi
                        	done
                               	if [ "$WIFIDOG" = "1" ]; then
					break;
                               	fi
                	done
		else 
			WIFIDOG=`/bin/grep httpRedirectStatus /var/config | /usr/bin/awk '{print $2}'`
		fi

		if [ $WIFIDOG = "1" ]; then
			PID=`/bin/pidof wifidog | /usr/bin/awk '{print $1}'`
			if [ ! $PID ] && [ $WD_TIMER -ge $WD_TIMEOUT ]; then
				logger -s "wifidog stopped!restarting it and hostapd from system monitoring script"
				/sbin/start-stop-daemon -S -x /usr/bin/wifidog -- -c /etc/wifidog.conf -s
				sleep 3
				#Restarting Hostapd to let the STA kickout for smooth operation of Redirection and CP Business Logic  
				${TRANSLATORS_BIN_LOCATION}/hostapd_tr < /var/config > ${NULL_DEVICE} 2>&1
				STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
				continue
			elif [ ! $PID ] && [ $WD_TIMER -lt $WD_TIMEOUT ]; then
				WD_TIMER=`expr $WD_TIMER + 1`
			elif [ $PID ]; then
				WD_TIMER=0
			fi
		fi

		PID=`/bin/pidof hostapd | /usr/bin/awk '{print $1}'`
		if [ ! $PID ] && [ $HAPD_TIMER -ge $HAPD_TIMEOUT ]; then
			logger -s "hostapd stopped! restarting it from system monitoring script" 
			${TRANSLATORS_BIN_LOCATION}/hostapd_tr < /var/config > ${NULL_DEVICE} 2>&1
			STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
			HAPD_TIMER=0
		elif [ ! $PID ] && [ $HAPD_TIMER -lt $HAPD_TIMEOUT ]; then
			HAPD_TIMER=`expr $HAPD_TIMER + 1`
		elif [ $PID ]; then
			HAPD_TIMER=0
		fi
		
		PID=`/bin/pidof nmbd | /usr/bin/awk '{print $1}'`
		if [ ! $PID ] && [ $NMBD_TIMER -ge $NMBD_TIMEOUT ]; then
			logger -s "nmbd stopped! restarting it from system monitoring script" 
			${TRANSLATORS_BIN_LOCATION}/nmbd_tr < /var/config > ${NULL_DEVICE} 2>&1
			STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
			continue
		elif [ ! $PID ] && [ $NMBD_TIMER -lt $NMBD_TIMEOUT ]; then
			NMBD_TIMER=`expr $NMBD_TIMER + 1`
		elif [ $PID ]; then
			NMBD_TIMER=0
		fi

		GCD=`/bin/grep cloudStatus /var/config | /usr/bin/awk '{print $2}'`
		if [ $GCD = "1" ]; then
			PID=`/bin/pidof pal.netgear | /usr/bin/awk '{print $1}'`
			if [ ! $PID ] && [ $GCD_TIMER -ge $GCD_TIMEOUT ]; then
				logger -s "pal.netgear stopped! restarting it from system monitoring script" 
				${TRANSLATORS_BIN_LOCATION}/pal_translator < /var/config > ${NULL_DEVICE} 2>&1
				STOPPED_PROC_COUNT=`expr $STOPPED_PROC_COUNT + 1`
				# sleeping of an one second is mandatory - to avoid corruption of /var/pal.cfg
				sleep 1
				continue
			elif [ ! $PID ] && [ $GCD_TIMER -lt $GCD_TIMEOUT ]; then
				GCD_TIMER=`expr $GCD_TIMER + 1`
			elif [ $PID ]; then
				GCD_TIMER=0
			fi
		fi
	fi

	STOPPED_PROC_COUNT=0
	sleep 2
done
