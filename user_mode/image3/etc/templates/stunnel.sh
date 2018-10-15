#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
config)
	rgdb -A $TROOT/stunnel/stunnelconf.php > /var/etc/stunnel.conf
	;;
start|restart)
# --- begin of certificate session ---
	spath="/sys/stunnel"
	epath="/sys/stunnel/ext_cert"
	path=`rgdb -g $spath/path`
	certname=`rgdb -g $spath/certname`
	#keyname=`rgdb -g $spath/keyname`

	[ -d "$path" ] && rm -rf $path
	mkdir -p $path

	ext_cert=`rgdb -g $epath/enable`
	block=`rgdb -g $epath/block`
	offset=`rgdb -g $epath/offset`
	size=`rgdb -g $epath/size`

	# check external status
	if [ "$ext_cert" = "0" -o "$certname" = "none" ]; then
		echo "External Certificate does not support." > /dev/console
		echo "Use default certificate." > /dev/console
		cp /etc/cert/* $path/.
		rgdb -s $spath/certname cert.pem
		rgdb -s $spath/keyname key.pem
		rgdb -s $spath/single_cert 0
		/etc/scripts/misc/profile.sh put
	else
		# check mtdblock has data or not.
		devconf get -n $block -o $offset -f $path/../stunnel.tar.gz > /dev/null 2>&1
		if [ "$?" != "0" ]; then
			echo "The data destroyed. Restore the default certificate." > /dev/console
			cp /etc/cert/* $path/.
			cd $path/..
			tar zcf stunnel.tar.gz `basename $path`
			devconf put -n $block -o $offset -z $size -f stunnel.tar.gz > /dev/null 2>&1
			if [ "$?" = "0" ]; then
				echo "The certificate has been restored." > /dev/console
				rgdb -s $spath/certname cert.pem
				rgdb -s $spath/keyname key.pem
				rgdb -s $spath/single_cert 0
				/etc/scripts/misc/profile.sh put
				rm -f stunnel.tar.gz
			else
				echo "System Error!!! Cannot save the certificate." > /dev/console
			fi
		else
			echo "Setting certificate." > /dev/console
			cd $path/..
			tar zxf stunnel.tar.gz
			rm -f stunnel.tar.gz
		fi
	fi
# --- end of certificate session ---
	[ -f /var/run/stunnel_stop.sh ] && sh /var/run/stunnel_stop.sh > /dev/console
	rgdb -A $TROOT/stunnel/stunnel_run.php -V start=1 > /var/run/stunnel_start.sh
	rgdb -A $TROOT/stunnel/stunnel_run.php -V start=0 > /var/run/stunnel_stop.sh
	sleep 1
	sh /var/run/stunnel_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/stunnel_stop.sh ]; then
		sh /var/run/stunnel_stop.sh > /dev/console
		rm -f /var/run/stunnel_stop.sh
	fi
	;;
*)
	echo "usage: $0 {start|stop|restart|config}"
	;;
esac
