<?
/* vi: set sw=4 ts=4: */
$wr_sta_mac = query("/runtime/webredirect/client_mac");
$wr_sta_mac_a = query("/runtime/webredirect/client_mac_a");
$wr_da_ip = query("/runtime/webredirect/daddr_ip_flag");
$wr_enable = query("/wlan/inf:1/webredirect/enable");

$wr_dip = fread("/var/proc/web/session:".$sid."/dip");
$cfg_lan_type = query("/wan/rg/inf:1/mode");
if($cfg_lan_type == 1)
{
	$cfg_ipaddr = query("/wan/rg/inf:1/static/ip");
}
else
{
	$cfg_ipaddr = query("/runtime/wan/inf:1/ip");
}

if($wr_sta_mac == "")
{
	$wr_sta_mac = 1;	
}
if($wr_sta_mac_a == "")
{
        $wr_sta_mac_a = 1;
}
if ($NO_NEED_AUTH!="1")
{
	/* for WEB based login  */
	require("/www/auth/__authenticate_s.php");
	
	if($wr_enable == 1 && $wr_dip != $cfg_ipaddr && $wr_dip != "0.0.0.0" && $wr_dip != "")
	{
		require("/www/session_wr_login.php"); 
		exit;
	}
	else
	{
	if($AUTH_RESULT=="401")		{require("/www/session_login.php"); exit;}
	if($AUTH_RESULT=="full")	{require("/www/session_full.php"); exit;}
	if($AUTH_RESULT=="timeout")	{require("/www/session_timeout.php"); exit;}
	}
	$AUTH_GROUP=fread("/var/proc/web/session:".$sid."/user/group");
}
else
{
	$AUTH_GROUP="";
	$AUTH_RESULT="";
}
require("/www/model/__lang_msg.php");
?>
