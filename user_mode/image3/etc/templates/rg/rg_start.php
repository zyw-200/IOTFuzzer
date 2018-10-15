#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
if (query("/runtime/router/enable") != 1)
{
	echo "echo Bridge mode selected, Router function is disabled ! > /dev/console\n";
	exit;
}
require($template_root."/rg/flush_readconfig.php");


$portt_pid="/var/run/portt.pid";
$LOG_STRING="-m limit --limit 10/m -j LOG --log-level info --log-prefix";
$rg_script=1;

/* create function nodes, so we can anchor */
set("/runtime/func/dummy", "0");
?>
# Flush all chains
iptables			-F
iptables -t nat		-F
iptables -t mangle	-F
# Delete all user-defined chains
iptables			-X
iptables -t nat		-X
iptables -t mangle	-X
# Default policy
iptables			-P INPUT ACCEPT
iptables			-P OUTPUT ACCEPT
iptables			-P FORWARD ACCEPT
iptables -t nat		-P PREROUTING ACCEPT
iptables -t nat		-P POSTROUTING ACCEPT
# Add custom chains
iptables -t mangle	-N PRE_MARK
iptables -t nat		-N PRE_DOS
iptables -t nat		-N PRE_SPI
iptables -t nat		-N PRE_UPNP
iptables -t nat		-N PRE_VRTSRV
iptables -t nat		-N PRE_MISC
iptables -t nat		-N PRE_DMZ
iptables -t nat		-N PRE_DEFAULT
iptables -t nat		-N PRE_MACFILTER
iptables -t nat		-N PRE_PORTT
iptables -t nat		-N PST_VRTSRV
iptables			-N FOR_DNAT
iptables			-N FOR_IPFILTER
iptables			-N FOR_VPN
iptables			-N FOR_BLOCKING
iptables			-N INP_BLOCKING
iptables			-N FOR_FIREWALL
iptables			-N FOR_PORTT
<?
anchor("/runtime/func");
set("dos",			"0");
set("dmz",			"0");
set("vpn",			"0");
set("ftp",			"0");
set("misc",			"0");
set("portt",		"0");
set("vrtsrv",		"0");
set("fastnat",		"1");
set("ipfilter",		"0");
set("firewall",		"0");
set("urlfilter",	"0");
set("macfilter",	"0");
?>
# add default rule (for incoming WAN interfaces)
iptables			-A FOR_DNAT -m conntrack --ctstate DNAT -j ACCEPT
iptables -t nat		-A PRE_DEFAULT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -t nat     -A PRE_DEFAULT -i ! <?=$lanif?> -p TCP --dport 80 -j LOG --log-level 6 --log-prefix "DRP:001:"
iptables -t nat		-A PRE_DEFAULT -j DROP
# DOS
iptables -t nat		-A PRE_DOS -p tcp -m limit --limit 10/s --limit-burst 50 -j RETURN
iptables -t nat		-A PRE_DOS -p tcp -j DROP
iptables -t nat		-A PRE_DOS -p icmp --icmp-type echo-request -m limit --limit 10/s --limit-burst 50 -j RETURN
iptables -t nat		-A PRE_DOS -p icmp --icmp-type echo-reply -m limit --limit 10/s --limit-burst 50 -j RETURN
iptables -t nat		-A PRE_DOS -p icmp <?=$LOG_STRING?> 'ATT:002[PING-FLOODING]:'
iptables -t nat		-A PRE_DOS -p icmp -j DROP
# SPI
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags SYN,ACK SYN,ACK -m state --state NEW <?=$LOG_STRING?> 'ATT:001[SYN-ACK]:'
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags SYN,ACK SYN,ACK -m state --state NEW -j DROP
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags ALL NONE <?=$LOG_STRING?> 'ATT:001[Null]:'
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags ALL NONE -j DROP
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags ALL FIN,URG,PSH <?=$LOG_STRING?> 'ATT:001[NMAP-Xmas]:'
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags ALL FIN,URG,PSH -j DROP
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags ALL ALL <?=$LOG_STRING?> 'ATT:001[Xmas]:'
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags ALL ALL -j DROP
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags ALL SYN,RST,ACK,FIN,URG <?=$LOG_STRING?> 'ATT:001[Xmas]:'
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags ALL SYN,RST,ACK,FIN,URG -j DROP
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags SYN,RST SYN,RST <?=$LOG_STRING?> 'ATT:001[SYN-RST]:'
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags SYN,RST SYN,RST -j DROP
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags SYN,FIN SYN,FIN <?=$LOG_STRING?> 'ATT:001[SYN-FIN]:'
iptables -t nat		-A PRE_SPI -p tcp --tcp-flags SYN,FIN SYN,FIN -j DROP
iptables -t nat		-A PRE_SPI -p tcp ! --syn -m state --state NEW <?=$LOG_STRING?> 'ATT:001[Xmas]:'
iptables -t nat		-A PRE_SPI -p tcp ! --syn -m state --state NEW -j DROP

<?
echo "portt &\n";
echo "echo $! > ".$portt_pid."\n";

echo "echo 0 > /proc/sys/net/ipv4/conf/default/rp_filter\n";
echo "echo 0 > /proc/sys/net/ipv4/conf/all/rp_filter\n";
echo "echo 1 > /proc/sys/net/ipv4/ip_forward\n";

require($template_root."/rg/flush_ipfilter.php");
require($template_root."/rg/flush_macfilter.php");
require($template_root."/rg/flush_blocking.php");
require($template_root."/rg/flush_passthrough.php");
require($template_root."/rg/flush_portt.php");
?>
exit 0
