#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$generate_start=1;
$mode = query("/wan/rg/inf:1/mode");
$capwap_enabled = query("/sys/wtp/capwap/enable");

if (query("/runtime/router/enable")!=1)
{
	echo "echo Bridge mode selected ! > /dev/console\n";
	if ($mode==1)
	{
		require($template_root."/wan_static.php");
		//if (query("/wlan/inf:1/webredirect/enable")==1)
		//{
			$sta_ip = query("/wan/rg/inf:1/static/ip");
		//}
	}
	else
	{
		$mode=2;
		if ($capwap_enabled != "1")
		{
			require($template_root."/wan_dhcp.php");
		}
	}

	require("/etc/templates/inet6.php");
}
else
{
	if		($mode==1)	{ require($template_root."/wan_static.php"); }
	else if	($mode==2 && $capwap_enabled!="1")	{ require($template_root."/wan_dhcp.php"); }
	else if	($mode==3)	{ require($template_root."/wan_pppoe.php"); }
	else if ($mode==4)	{ require($template_root."/wan_ip_setup.php"); }
	else if ($mode==5)	{ require($template_root."/wan_ip_setup.php"); }
	else if ($mode==7 && $capwap_enabled!="1")	{ require($template_root."/wan_dhcp.php"); }
	else
	{
		echo "echo \"Uknown WAN mode : ".$mode."\" > /dev/console\n";
	}
}
?>
