<?
/* fatlady is used to validate the configuration for the specific service.
 * FATLADY_prefix was defined to the path of Session Data.
 * 3 variables should be returned for the result:
 * FATLADY_result, FATLADY_node & FATLADY_message. */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/inf.php";
include "/htdocs/phplib/inet.php";


$level = query($FATLADY_prefix."/device/log/level");
$remote =query($FATLADY_prefix."/device/log/remote");
TRACE_debug("FATLADY: DEVICE.LOG: log level=".$level);

if($level=="WARNING" || $level=="NOTICE" || $level=="DEBUG")
{
	set($FATLADY_prefix."/valid", "1");
	$_GLOBALS["FATLADY_result"]  = "OK";
	$_GLOBALS["FATLADY_node"]    = "";
	$_GLOBALS["FATLADY_message"] = "";

}
else
{
	$_GLOBALS["FATLADY_result"]  = "FAILED";
	$_GLOBALS["FATLADY_node"]    = $FATLADY_prefix."/device/log/level";
	$_GLOBALS["FATLADY_message"] = i18n("Unsupported log level be assigned");	/* internal error, no i18n. */
}
function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"] = $result;
	$_GLOBALS["FATLADY_node"]   = $node;
	$_GLOBALS["FATLADY_message"]= $message;
}

function check_syslog_setting($path, $addrtype, $lan_ip, $mask)
{
	if (query($path."/enable") == "1")
	{
		$hostid = query($path."/ipv4/ipaddr");
		if ($hostid == "")
		{
			set_result("FAILED", $path."/ipv4/ipaddr", i18n("SYSLOG host cannot be empty."));
			return "FAILED";
		}
		if ($hostid <=0)
		{
			set_result("FAILED", $path."/ipv4/ipaddr", i18n("SYSLOG host is not a valid host ID."));
			return "FAILED";
		}
		if ($addrtype == "ipv4")
		{
			$syslogip = ipv4ip($lan_ip, $mask, $hostid);
			if (INET_validv4host($syslogip, $mask)==0)
			{
				set_result("FAILED",$path."/ipv4/ipaddr",i18n("SYSLOG host is not a valid host ID."));
				return "FAILED";
			}
		}
	}
	else set($path."/enable", "0");
	return "OK";
}

$infp = XNODE_getpathbytarget("/runtime", "inf", "uid", "LAN-1", 0);
$addrtype = query($infp."/inet/addrtype");
if ($addrtype == "ipv4")
{
	$lan_ip = query($infp."/inet/ipv4/ipaddr");
	$mask = query($infp."/inet/ipv4/mask");
}
//TRACE_debug("[Fatlady] infp:".$infp." lan ip:".$lan_ip." mask:".$mask);

set_result("FAILED","","");
if (check_syslog_setting($FATLADY_prefix."/device/log/remote", $addrtype, $lan_ip, $mask)=="OK")
{
	set($FATLADY_prefix."/valid", "1");
	set_result("OK", "", "");
}



?>