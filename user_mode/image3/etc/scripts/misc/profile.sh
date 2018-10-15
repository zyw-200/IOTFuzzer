#!/bin/sh
echo [$0] $1 ... > /dev/console
case "$1" in
get)
	devconf get -f /var/run/rgdb.xml.gz
	if [ "$?" != "0" ]; then
		echo "CAN NOT get devive config, generate default!" > /dev/console
		/etc/scripts/misc/profile.sh reset
		exit 0
	fi
	gunzip /var/run/rgdb.xml.gz
	rgdb -l /var/run/rgdb.xml
	if [ "$?" != "0" ]; then
		echo "Invalid config, generate default!" > /dev/console
		/etc/scripts/misc/profile.sh reset
	else
		/etc/scripts/misc/defnodes.sh
	fi
	rm -f /var/run/rgdb.xml
	;;
getnew)
	devconf get -f /var/run/rgdb.xml.gz
	if [ "$?" != "0" ]; then
		echo "CAN NOT get devive config, generate default!" > /dev/console
		/etc/scripts/misc/profile.sh reset
		exit 0
	fi
	gunzip /var/run/rgdb.xml.gz
	rgdb -l /var/run/rgdb.xml
	if [ "$?" != "0" ]; then
		echo "Invalid config, generate default!" > /dev/console
		/etc/scripts/misc/profile.sh reset
	else
		if [ "`rgdb -g /firmware/update`" != "0" ]; then
			rgdb -s /firmware/update 0
			cp /etc/config/defaultvalue.gz /var/run/default.xml.gz
			gunzip /var/run/default.xml.gz
			rgdb -p /var/run/default.xml
			rm -f /var/run/default.xml
			rm -f /var/run/rgdb.xml
			rgdb -D /var/run/rgdb.xml
			gzip /var/run/rgdb.xml
			devconf put -f /var/run/rgdb.xml.gz
			if [ "$?" = "0" ]; then
				echo "ok" > /dev/console
			else
				echo "failed" > /dev/console
			fi
			rm -f /var/run/rgdb.xml.gz
		fi
		/etc/scripts/misc/defnodes.sh	
	fi
	rm -f /var/run/rgdb.xml
	;;
put)
	rgdb -s /sys/restore_default 1
	rgdb -D /var/run/rgdb.xml
	gzip /var/run/rgdb.xml
	devconf put -f /var/run/rgdb.xml.gz
	if [ "$?" = "0" ]; then
		echo "ok" > /dev/console
	else
		echo "failed" > /dev/console
	fi
	rm -f /var/run/rgdb.xml.gz
	cd
	TR069=`rgdb -g /tr069v3/enable`
	if [ "$TR069" = "1" ]; then
		submit TR069V3_UPDATE_NODE
	fi
	;;
reset)
	if [ "$2" != "" ]; then
		cp $2 /var/run/rgdb.xml.gz
		rm -f $2
	else
		cp /etc/config/defaultvalue.gz /var/run/rgdb.xml.gz
	fi
	gunzip /var/run/rgdb.xml.gz
	rgdb -l /var/run/rgdb.xml
	/etc/scripts/misc/defnodes.sh
	if [ -f /etc/scripts/freset_setnodes.sh -a "$2" = "" ]; then
		sh /etc/scripts/freset_setnodes.sh
	fi
	if [ -f /usr/sbin/captival_tar ]; then
		captival_tar default
	fi
	rm -f /var/run/rgdb.xml
	cd
	rgdb -s /sys/restore_default 0
	rgdb -D /var/run/rgdb.xml
	gzip /var/run/rgdb.xml
	devconf put -f /var/run/rgdb.xml.gz
	if [ "$?" = "0" ]; then
		echo "ok" > /dev/console
	else
		echo "failed" > /dev/console
	fi
	rm -f /var/run/rgdb.xml.gz
	cd
	TR069=`rgdb -g /tr069v3/enable`
	if [ "$TR069" = "1" ]; then
		submit TR069V3_UPDATE_NODE
	fi
	;;
*)
	echo "Usage: $0 get/put/reset"
esac
