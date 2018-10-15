<?
include "/htdocs/phplib/xnode.php";

if($ACTION=="INIT_SMART404")
{
	echo 'xmldbc -s /runtime/smart404 "1"\n';
	echo 'xmldbc -s /runtime/smart404/fakedns "0"\n';
	echo 'ln -s /usr/sbin/dnsmasq /var/run/fakedns\n';
	echo '/var/run/fakedns --port=63481 --address=/#/1.33.203.39\n';
}

if($ACTION=="INIT_EVENTS")
{
	echo 'event WAN-1.UP add "xmldbc -s /runtime/smart404/wan1up 1; service INFSVCS.WAN-1 restart"\n';
	echo 'event WAN-1.DOWN add "xmldbc -s /runtime/smart404/wan1up 0; service INFSVCS.WAN-1 stop"\n';
	echo 'event WAN-2.UP add "xmldbc -s /runtime/smart404/wan2up 1; service INFSVCS.WAN-2 restart"\n';
	echo 'event WAN-2.DOWN add "xmldbc -s /runtime/smart404/wan2up 0; service INFSVCS.WAN-2 stop"\n';
	echo 'event WANPORT.LINKUP insert SMART404:"xmldbc -s /runtime/smart404/wanlink 1;phpsh /etc/events/update_smart404.php"\n';
	echo 'event WANPORT.LINKDOWN insert SMART404:"xmldbc -s /runtime/smart404/wanlink 0;phpsh /etc/events/update_smart404.php"\n';
	echo 'event SMART404.ENABLE insert SMART404:"phpsh /etc/scripts/control_smart404.php ACTION=ENABLE"\n';
	echo 'event SMART404.DISABLE insert SMART404:"phpsh /etc/scripts/control_smart404.php ACTION=DISABLE"\n';
}

if($ACTION=="START_DHCP")
{
	echo 'event WANPORT.LINKUP insert SMART404:"xmldbc -s /runtime/smart404/wanlink 1"\n';
	echo 'event WANPORT.LINKDOWN insert SMART404:"xmldbc -s /runtime/smart404/wanlink 0"\n';
}

if($ACTION=="STOP_DHCP")
{
	echo 'event WANPORT.LINKUP insert SMART404:"xmldbc -s /runtime/smart404/wanlink 1;phpsh /etc/events/update_smart404.php"\n';
	echo 'event WANPORT.LINKDOWN insert SMART404:"xmldbc -s /runtime/smart404/wanlink 0;phpsh /etc/events/update_smart404.php"\n';
}

if($ACTION=="ENABLE")
{
	echo 'xmldbc -s /runtime/smart404 1\n';
	echo 'phpsh /etc/events/update_smart404.php\n';
}

if($ACTION=="DISABLE")
{
	echo 'xmldbc -s /runtime/smart404 0\n';
	//remove all rules
	echo 'iptables -t nat -D PREROUTING -p tcp --dport 53 -j REDIRECT --to-ports 63481\n';
	echo 'iptables -t nat -D PREROUTING -p udp --dport 53 -j REDIRECT --to-ports 63481\n';
	echo 'iptables -t nat -D PREROUTING -p tcp --dport 80 -j REDIRECT --to-ports 80\n';
	echo 'iptables -t nat -D PREROUTING -d 1.33.203.39 -p tcp --dport 80 -j REDIRECT --to-ports 80\n';
	//clear dns contrack cached
	echo 'echo 1 > /proc/nf_conntrack_flush\n';
}

?>
