<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inet.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"]	= $result;
	$_GLOBALS["FATLADY_node"]	= $node;
	$_GLOBALS["FATLADY_message"]= $message;
}

function check_ppp4($path)
{
	anchor($path);

	$over = query("over");
	if ($over != "eth" && $over != "pptp" && $over != "l2tp" && $over != "tty")
	{
		/* Internal error, no i18n. */
		set_result("FAILED", $path."/ipaddr", "Illegal value for over : ".$over);
		return;
	}

	/* IP address */
	$static = query("static");
	if ($static == "1")
	{
		$ipaddr = query("ipaddr");
		if (INET_validv4addr($ipaddr)==0)
		{
			set_result("FAILED",$path."/ipaddr",i18n("Invalid IP address"));
			return;
		}
	}
	else
	{
		/* if static is not 1, it should be 0. */
		set("static", "0");
		del("ipaddr");
	}

	/* DNS */
	$cnt = query("dns/count");
	$i = 0;
	while ($i < $cnt)
	{
		$i++;
		$value = query("dns/entry:".$i);
		if (INET_validv4addr($value)==0)
		{
			set_result("FAILED",$path."/dns:".$i, i18n("Invalid DNS address"));
			return;
		}
	}

	/* MTU/MRU */
	$mtu = query("mtu");
	if ($mtu != "")
	{
		if (isdigit($mtu)=="0")
		{
			set_result("FAILED",$path."/mtu",
				i18n("The MTU value is invalid."));
			return;
		}
		if ($mtu < 576)
		{
			set_result("FAILED",$path."/mtu",
				i18n("The MTU value is too small, the valid value mustn't be smaller than 576."));
			return;
		}
		if ($over=="pptp" && $mtu > 1400)
		{
			set_result("FAILED",$path."/mtu",
				i18n("The MTU value is too large, the valid value for pptp is 576 ~ 1400."));
			return;
		}
		else if ($over=="l2tp" && $mtu > 1400)
		{
			set_result("FAILED",$path."/mtu",
				i18n("The MTU value is too large, the valid value for l2tp is 576 ~ 1400."));
			return;
		}
		else if ($mtu > 1492)
		{
            if($over == "tty")
            {
                if($mtu >1500)
                {
                    set_result("FAILED",$path."/mtu",
                            i18n("The MTU value is too large, the valid value for 3G is 576 ~ 1500."));
                    return;
                }
            }
            else
            {
                set_result("FAILED",$path."/mtu",
                    i18n("The MTU value is too large, the valid value is 576 ~ 1492."));
                return;
            }
		}
		$mtu = $mtu + 1 - 1; /* convert to number */
		set("mtu", $mtu);
	}
	$mru = query("mru");
	if ($mru != "")
	{
		if (isdigit($mru)=="0")
		{
			set_result("FAILED",$path."/mtu",
				i18n("The MRU value is invalid."));
			return;
		}
		if ($mru < 576)
		{
			set_result("FAILED",$path."/mru",
				i18n("The MRU value is too small, the valid value is 576 ~ 1492."));
			return;
		}
		if ($mru > 1492)
		{
			set_result("FAILED",$path."/mru",
				i18n("The MRU value is too large, the valid value is 576 ~ 1492."));
			return;
		}
		$mru = $mru + 1 - 1; /* convert to number */
		set("mru", $mru);
	}

	/* User Name & Password */
	if (query("username")=="" && $over != "tty")
	{
		set_result("FAILED",$path."/username",i18n("The user name can not be empty"));
		return;
	}

	/* dialup */
	$mode = query("dialup/mode");
	if ($mode != "auto" && $mode != "manual" && $mode != "ondemand")
	{
		/* no i18n */
		set_result("FAILED",$path."/dialup/mode","Invalid value for dial up mode - ".$mode);
		return;
	}
	$tout = query("dialup/idletimeout");
	if ($tout != "")
	{
		if (isdigit($tout)=="0" || $tout < 0 || $tout > 10000)
		{
			set_result("FAILED",$path."/dialup/mode",
				i18n("Invalid value for idle timeout."));
			return;
		}
	}

	if ($over == "eth")
	{
		/* should check service name & ac name here. */
	}
	else if ($over == "pptp")
	{
		$server = query("pptp/server");
		if ($server=="")
		{
			set_result("FAILED",$path."/pptp/server", i18n("No PPTP server."));
			return;
		}
		if (INET_validv4addr($server)==0 && isdomain($server)!=1)
		{
			set_result("FAILED",$path."/pptp/server",i18n("Invalid server IP address"));
			return;
		}
	}
	else if ($over == "l2tp")
	{
		$server = query("l2tp/server");
		if ($server=="")
		{
			set_result("FAILED",$path."/l2tp/server", i18n("No L2TP server."));
			return;
		}
		if (INET_validv4addr($server)==0 && isdomain($server)!=1)
		{
			set_result("FAILED",$path."/l2tp/server",i18n("Invalid server IP address"));
			return;
		}
	}

	set_result("OK","","");
}

TRACE_debug("FATLADY: INET: inetentry=[".$_GLOBALS["FATLADY_INET_ENTRY"]."]");
set_result("FAILED","","");
if ($_GLOBALS["FATLADY_INET_ENTRY"]=="") set_result("FAILED","","No XML document");
else check_ppp4($_GLOBALS["FATLADY_INET_ENTRY"]."/ppp4");
?>
