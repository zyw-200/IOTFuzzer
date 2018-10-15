# pppoe >>>>
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$rg_script=1;
$pppoe_session1="session1";

if ($generate_start == 1)
{
	anchor("/runtime/layout");
	$wanif=query("wanif");
	$orig_wanmac=query("wanmac");
	$curr_wanmac=query("/runtime/wan/inf:1/mac");
	$clonemac=query("/wan/rg/inf:1/pppoe/clonemac");
	require($template_root."/clone_wanmac.php");

	/* Generate authentication info */
	echo "[ -f /etc/ppp/chap-secrets ] && rm -f /etc/ppp/chap-secrets\n";
	echo "echo -n > /etc/ppp/pap-secrets\n";
	echo "ln -s /etc/ppp/pap-secrets /etc/ppp/chap-secrets\n";

	/* Prepare ip-up ip-down */
	echo "cp ".$template_root."/ppp/ip-up /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ip-down /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ppp-status /etc/ppp/.\n";

	set("/runtime/wan/inf:1/connecttype", "3");
	anchor("/runtime/wan/inf:1");
	set("ip", "");
	set("netmask", "");
	set("gateway", "");
	set("primarydns", "");
	set("secondarydns", "");
	set("mtu", "");

	$ppp_linkname	= $pppoe_session1;

	/* Query PPP parameters */
	anchor("/wan/rg/inf:1/pppoe");
	$ppp_type			= "pppoe";
	if (query("mode")==1)	{ $ppp_staticip = query("staticip"); }
	else					{ $ppp_staticip = ""; }
	$ppp_username		= queryjs("user");
	$ppp_password		= queryjs("password");
	$ppp_idle			= query("idletimeout");
	$ppp_persist		= query("autoReconnect");
	$ppp_demand			= query("onDemand");
	$ppp_usepeerdns		= query("autodns");
	$ppp_mtu			= query("mtu");
	$ppp_mru			= query("mtu");
	$pppoe_type			= 0;
	$ppp_defaultroute	= 1;
	if ($ppp_demand!=1) { $ppp_idle=0; }

	set("/runtime/wan/inf:1/pppoetype", $pppoe_type); /* 0: pppoe, 1, unumberred ip, 2: unumberred ip + private */

	/* PPPoE parameters */
	$pppoe_acname  = query("acname");
	$pppoe_svcname = query("acservice");
	$pppoe_if      = query("/runtime/layout/wanif");

	echo "echo \"\\\"".$ppp_username."\\\" * \\\"".$ppp_password."\\\" *\" >> /etc/ppp/pap-secrets\n";
	require($template_root."/ppp/ppp_setup.php");
	if ($ppp_persist == 1) { echo "sh /var/run/ppp-".$ppp_linkname.".sh start > /dev/console\n"; }
	if ($ppp_demand == 1)
	{
		set("/runtime/wan/inf:1/connecttype", "3");
		anchor("/runtime/wan/inf:1");
		set("ip", "10.112.112.112");
		set("netmask", "255.255.255.255");
		set("gateway", "10.112.112.113");
		set("primarydns", "10.112.112.114");
	}
}
else
{
	echo "sh /var/run/ppp-".$pppoe_session1.".sh stop > /dev/console\n";
}
?>
# pppoe <<<
