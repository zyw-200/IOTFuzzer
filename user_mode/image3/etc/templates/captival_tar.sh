#!/bin/sh
echo [$0] $1 $2.....> /dev/console

case "$1" in
save)
	echo "write tar to flash.....">/dev/console;
	captival_tar save;
	;;
delet)
	echo "delet $2 .......">/dev/console;
	captival_tar delet $2;
	;;
*)
	echo "usage:captival_tar delet | save">/dev/console;
	;;
esac
