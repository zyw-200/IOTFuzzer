#!/bin/sh
echo [$0] $1 ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$rg_script=1;

$wanmode = query("/wan/rg/inf:1/mode");
if		($wanmode == "4")	{ anchor("/wan/rg/inf:1/pptp"); $PPPMODE="PPTP"; }
else if	($wanmode == "5")	{ anchor("/wan/rg/inf:1/l2tp"); $PPPMODE="L2TP"; }
else
{
	echo "echo \"Not in PPTP/L2TP mode ...\" > /dev/console\n";
	exit;
}

$ppp_linkname = "session1";

if ($generate_start==1)
{
	if ($server == "")
	{
		echo "echo No server available ... > /dev/console\n";
		exit;
	}

	/* generate authentication info */
	echo "[ -f /etc/ppp/chap-secrets ] && rm -f /etc/ppp/chap-secrets\n";
	echo "echo -n > /etc/ppp/pap-secrets\n";
	echo "ln -s /etc/ppp/pap-secrets /etc/ppp/chap-secrets\n";

	/* Prepare ip-up, ip-down and ppp-status */
	echo "cp ".$template_root."/ppp/ip-up /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ip-down /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ppp-status /etc/ppp/.\n";


	/* query ppp parameters */
	if ($PPPMODE == "PPTP")	{ $ppp_type = "pptp"; $pptp_server=$server; }
	else					{ $ppp_type = "l2tp"; $l2tp_server=$server; }
	$ppp_staticip	= "";
	$ppp_username	= query("user");
	$ppp_password	= queryjs("password");
	$ppp_idle		= query("idletimeout");
	$ppp_persist	= query("autoreconnect");
	$ppp_demand		= query("ondemand");
	$ppp_usepeerdns	= "1";	//query("autodns");
	$ppp_mtu		= query("mtu");
	$ppp_mru		= query("mtu");
	$ppp_defaultroute = 1;
	if ($ppp_demand!=1)	{ $ppp_idle=0; }

	echo "echo \"\\\"".$ppp_username."\\\" * \\\"".$ppp_password."\\\" *\" >> /etc/ppp/pap-secrets\n";
	require($template_root."/ppp/ppp_setup.php");
	if ($ppp_persist == 1) { echo "sh /var/run/ppp-".$ppp_linkname.".sh start > /dev/console\n"; }
	if ($ppp_demand == 1)
	{
		if ($PPPMODE == "PPTP")	{ set("/runtime/wan/inf:1/connecttype", "4"); }
		else					{ set("/runtime/wan/inf:1/connecttype", "5"); }
		anchor("/runtime/wan/inf:1");
		set("ip", "10.112.112.112");
		set("netmask", "255.255.255.255");
		set("gateway", "10.112.112.113");
		set("primarydns", "10.112.112.114");
	}
}
else
{
	echo "echo Stop WAN ".$PPPMODE." ... > /dev/console\n";
	echo "sh /var/run/ppp-".$ppp_linkname.".sh stop > /dev/console\n";
}
?>
