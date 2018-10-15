<?
/* setcfg is used to move the validated session data to the configuration database.
 * The variable, 'SETCFG_prefix',  will indicate the path of the session data. */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";


	$syslogenable	= query($SETCFG_prefix."/device/log/remote/enable");
	$sysloghostid	= query($SETCFG_prefix."/device/log/remote/ipv4/ipaddr");

	set("/device/log/remote/enable",	$syslogenable);
	set("/device/log/remote/ipv4/ipaddr/",	$sysloghostid);
	

 
$level = query($SETCFG_prefix."/device/log/level");

TRACE_debug("SETCFG/DEVICE.LOG: /device/log/level = ".$level);
set("/device/log/level", $level);

set("/device/log/email", "");
movc($SETCFG_prefix."/device/log/email", "/device/log/email");
?>
