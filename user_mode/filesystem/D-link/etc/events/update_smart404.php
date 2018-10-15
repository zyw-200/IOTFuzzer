<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inf.php";

function enable_fakedns()
{
	echo "xmldbc -s /runtime/smart404/fakedns 1\n";

	//redirect all HTTP access to 404 page
	//remove this rule because the next rule will handle all http requests
	echo "iptables -t nat -D PREROUTING -d 1.33.203.39 -p tcp --dport 80 -j REDIRECT --to-ports 80\n";

	echo "iptables -t nat -D PREROUTING -p tcp --dport 80 -j REDIRECT --to-ports 80\n";
	echo "iptables -t nat -I PREROUTING -p tcp --dport 80 -j REDIRECT --to-ports 80\n";

	//redirect all DNS to fakedns
    echo "iptables -t nat -D PREROUTING -p udp --dport 53 -j REDIRECT --to-ports 63481\n";
    echo "iptables -t nat -I PREROUTING -p udp --dport 53 -j REDIRECT --to-ports 63481\n";
	//clear dns contrack cached
	echo "echo 1 > /proc/nf_conntrack_flush\n";
	
	echo "iptables -t nat -D PREROUTING -p tcp --dport 53 -j REDIRECT --to-ports 63481\n";
	echo "iptables -t nat -I PREROUTING -p tcp --dport 53 -j REDIRECT --to-ports 63481\n";
}

function disable_fakedns()
{
	echo "xmldbc -s /runtime/smart404/fakedns 0\n";

	//cancel all rules
	echo "iptables -t nat -D PREROUTING -p tcp --dport 53 -j REDIRECT --to-ports 63481\n";
    echo "iptables -t nat -D PREROUTING -p udp --dport 53 -j REDIRECT --to-ports 63481\n";
	echo "iptables -t nat -D PREROUTING -p tcp --dport 80 -j REDIRECT --to-ports 80\n";
	//clear dns contrack cached
	echo "echo 1 > /proc/nf_conntrack_flush\n";
	//avoid someone to access the Internet by this bad ip
	echo "iptables -t nat -D PREROUTING -d 1.33.203.39 -p tcp --dport 80 -j REDIRECT --to-ports 80\n";
	echo "iptables -t nat -I PREROUTING -d 1.33.203.39 -p tcp --dport 80 -j REDIRECT --to-ports 80\n";
}

function is_ppp_class()
{
	$addr_type = INF_getcurraddrtype("WAN-1");

	if($addr_type == "ppp4" || $addr_type == "ppp6")
		return "true";
	
	return "false";
}

function wan_is_ready()
{
	$wanlink = query("/runtime/smart404/wanlink");

	if($wanlink != "1")
		return "false"; //cable is not connected

	$is_ppp = is_ppp_class();

	if($is_ppp == "true")
		return "true"; //in ppp mode, we only care about phy link status

	$wan1up = query("/runtime/smart404/wan1up");

	if($wan1up == "1")
		return "true";
	else
		return "false";
}

$smart404_enable = query("/runtime/smart404");

//smart404 disable
if($smart404_enable != "1")
	exit;

//not in router mode, remove all rules of fakedns
$device_layout = query("/device/layout");
if($device_layout != "router")
{
	disable_fakedns();
	exit;
}

if(wan_is_ready() == "true")
	disable_fakedns();
else
	enable_fakedns();
?>
