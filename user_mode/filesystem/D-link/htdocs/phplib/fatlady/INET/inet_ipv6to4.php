<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inet.php";
include "/htdocs/phplib/inet6.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"] = $result;
	$_GLOBALS["FATLADY_node"]   = $node;
	$_GLOBALS["FATLADY_message"]= $message;
}

function check_inf6to4($path)
{
	$value  = query($path."/inf6to4/mode");
	$ipaddr = query($path."/inf6to4/ipaddr");
	$prfx = query($path."/inf6to4/prefix");
	$relay  = query($path."/inf6to4/relay");
	
	if ($value == "6RD")
	{	
		TRACE_debug("FATLADY: INF: 6to4 ipaddr=".$ipaddr);
		TRACE_debug("FATLADY: INF: 6to4 prefix=".$prfx);

		if (INET_validv6addr($ipaddr) == 0)
		{		
			return set_result("FAILED", $path."/inf6to4/ipaddr", i18n("Invalid IPv6 address"));
		}
		if ($prfx == "")
		{
			return set_result("FAILED", $path."/inf6to4/prefix", i18n("No prefix value"));
		}		
		if (isdigit($prfx) == 0)
		{
			return set_result("FAILED", $path."/inf6to4/prefix", i18n("Prefix value must be digit number"));
		}	
		if ($prfx <= 0 || prfx > 128)
		{
			return set_result("FAILED", $path."/inf6to4/prefix", i18n("Invalid prefix value"));
		}	
	}
	if ($relay != "")
	{	
		TRACE_debug("FATLADY: INF: 6to4 relay=".$relay);
		if (INET_validv4addr($relay) == 0)
		{
			return set_result("FAILED", $path."/inf6to4/relay", i18n("Invalid IPv4 address"));
		}
	}	
	set_result("OK","","");
}

set_result("FAILED","","");
$path = XNODE_get_var("FATLADY_6TO4_PATH");
if ($path=="")
	set_result("FAILED","","No XML document");
else
	check_inf6to4($path);
?>

