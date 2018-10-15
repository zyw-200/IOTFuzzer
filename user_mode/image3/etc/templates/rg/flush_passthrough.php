# VPN passthrough >>>
iptables -F FOR_VPN
<? /* vi: set sw=4 ts=4: */
$pptp = query("/nat/passthrough/pptp");
$ipsec = query("/nat/passthrough/ipsec");

if ($pptp != 0)
{
	echo "logger -p 192.0 SYS:016[PPTP]\n";
}
else
{
	echo "iptables -A FOR_VPN -p tcp --dport 1723 -j DROP\n"; 
	echo "logger -p 192.0 SYS:017[PPTP]\n";
}

if ($ipsec != 0)
{
	echo "logger -p 192.0 SYS:016[IPSec]\n";
}
else
{
	echo "iptables -A FOR_VPN -p udp --dport 500 -j DROP\n"; 
	echo "iptables -A FOR_VPN -p ah -j DROP\n"; 
	echo "iptables -A FOR_VPN -p esp -j DROP\n"; 
	echo "logger -p 192.0 SYS:017[IPSec]\n";
}
?>
# VPN passthrough <<<
