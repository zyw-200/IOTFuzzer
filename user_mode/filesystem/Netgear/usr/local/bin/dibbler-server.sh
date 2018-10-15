if [ -e "/tmp/dibbler-server.txt" ]; then
	/bin/rm -rf /var/lib/dibbler
	/bin/mkdir -p /var/lib/dibbler
	sleep 45
	/usr/local/bin/dibbler-server start
fi
