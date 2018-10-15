#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
path="/var/etc/certs"
block="/dev/mtdblock/5"
offset="20480"
size="20480"
tarfile="/var/etc/certs.tar.gz"

case "$1" in
CREATE_DIR)
	# create directories for different certs
	mkdir /var/etc/certs
	mkdir /var/etc/certs/hostapd
	mkdir /var/etc/certs/wpa_supplicant
	echo "Create Certificate Folder (hostapd,wpa_supplicant). OK!" > /dev/console
	;;
GET_CERT)
	# get certs data
	[ -d "$path" ] && rm -rf $path
	mkdir /var/etc/certs
	mkdir /var/etc/certs/hostapd
	mkdir /var/etc/certs/wpa_supplicant
	devconf get -n $block -o $offset -z $size -f $tarfile
	if [ "$?" != "0" ]; then
		echo "Getting External certificate for hostapd & wpa_supplicant. Fail!" > /dev/console
	else
		echo "Getting External certificate for hostapd & wpa_supplicant. OK!" > /dev/console
		tar zxf $tarfile -C /
		rm -f $tarfile
	fi
	;;
PUT_CERT)
	# put certs data
	tar zcf $tarfile $path
	devconf put -n $block -o $offset -z $size -f $tarfile
	echo `devconf dump -n $block -o $offset -z $size` > /dev/console
	rm -f $tarfile
	echo "Putting External certificate for hostapd & wpa_supplicant. OK!" > /dev/console
	;;
*)
	echo "usage: $0 {GET_CERT|PUT_CERT}"
	;;
esac
