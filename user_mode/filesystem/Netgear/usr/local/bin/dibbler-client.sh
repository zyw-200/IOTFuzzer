if [ -e "/tmp/dibbler-client.txt" ]; then
	sleep 20
	/bin/rm -rf /var/lib/dibbler
	/bin/mkdir -p /var/lib/
	/bin/mkdir -p /var/lib/dibbler
	/usr/local/bin/dibbler-client start
	sleep 5
	/usr/local/bin/hostapd_tr < /var/config 
fi
