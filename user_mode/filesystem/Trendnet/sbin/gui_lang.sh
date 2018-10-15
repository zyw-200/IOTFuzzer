#!/bin/sh

if [ -f "/flash/lang.js.bz2" ]; then
	echo "language pack exists..."
	if [ -f "/flash/lang_chksum" ]; then
		cp -f /flash/lang.js.bz2 /var/tmp/lang.js.bz2
		bunzip2 /var/tmp/lang.js.bz2
	else
		cp -f /www/def_lang.js /var/tmp/lang.js
	fi
fi

if [ ! -f "/var/tmp/lang.js" ]; then
	echo "decompress language pack failed or language pack not found, copy the default one..."
	cp -f /www/def_lang.js /var/tmp/lang.js
fi

echo "do language merge..."
lang_merge -o /var/tmp/lang.js -d /www/def_lang.js -f /flash/lang.js


ln -s /www/def_lang_w.js /var/tmp/lang_w.js
