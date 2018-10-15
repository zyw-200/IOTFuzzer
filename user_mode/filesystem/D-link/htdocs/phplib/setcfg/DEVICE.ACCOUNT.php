<?
/* setcfg is used to move the validated session data to the configuration database.
 * The variable, 'SETCFG_prefix',  will indicate the path of the session data. */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";

$cnt = query($SETCFG_prefix."/device/account/count");
TRACE_debug("SETCFG: DEVICE.ACCOUNT got ".$cnt." accounts");
set("/device/account/count", $cnt);
$i = 0;
while ($i < $cnt)
{
	$i++;
	$name	= query($SETCFG_prefix."/device/account/entry:".$i."/name");
	$pwd	= query($SETCFG_prefix."/device/account/entry:".$i."/password");
	$grp	= query($SETCFG_prefix."/device/account/entry:".$i."/group");
	set("/device/account/entry:".$i."/name",	$name);
	if ($pwd!="==OoXxGgYy==")
		set("/device/account/entry:".$i."/password",$pwd);
	set("/device/account/entry:".$i."/group",	$grp);
}

$captcha = query($SETCFG_prefix."/device/session/captcha");
if ($captcha!="0")
	set("/device/session/captcha", 1);
else
	set("/device/session/captcha", 0);
?>
