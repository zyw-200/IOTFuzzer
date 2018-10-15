#!/bin/sh
echo [$0] ...> /dev/console
<? /* vi: set sw=4 ts=4: */
if (query("/runtime/router/enable")!=1)
{
	echo "echo Bridge mode selected, Router function is off ! > /dev/console\n";
	exit;
}
$PORTT_PID="/var/run/portt.pid";
?>
rgdb -i -d /runtime/func
echo 0 > /proc/sys/net/ipv4/ip_forward

echo "Stop PORTT ..." > /dev/console
if [ -f <?=$PORTT_PID?> ]; then
	pid=`cat <?=$PORTT_PID?>`
	if [ $pid != 0 ]; then
		kill $pid
	fi
	rm -f <?=$PORTT_PID?>
fi

iptables			-F
iptables -t nat		-F
iptables			-X
iptables -t nat		-X
iptables -t mangle	-X
iptables			-P INPUT ACCEPT
iptables			-P OUTPUT ACCEPT
iptables			-P FORWARD ACCEPT
iptables -t nat		-P PREROUTING ACCEPT
iptables -t nat		-P POSTROUTING ACCEPT

exit 0
