<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inet.php";
include "/htdocs/phplib/inf.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"]	= $result;
	$_GLOBALS["FATLADY_node"]	= $node;
	$_GLOBALS["FATLADY_message"]= $message;
}

function check_ipv4($path, $needgw)
{
	anchor($path);
	$static = query("static");
	if ($static != "1") set("static", "0");
	TRACE_debug("FATLADY: INET_IPV4: static = ".$static);
	if ($static == "1")
	{
		$ip = query("ipaddr");
		$mask = query("mask");
		$dhcps4 = INF_getinfinfo($_GLOBALS["FATLADY_INF_UID"], "dhcps4");

		TRACE_debug("FATLADY: INET_IPV4: ip = ".$ip);
		TRACE_debug("FATLADY: INET_IPV4: mask = ".$mask);
		if (INET_validv4addr($ip)==0)
		{
			set_result("FAILED", $path."/ipaddr", i18n("Invalid IP address"));
			return;
		}
		if ($mask=="")
		{
			set_result("FAILED", $path."/mask", i18n("No Subnet Mask value"));
			return;
		}
		if ($mask <0 || $mask >32)
		{
			set_result("FAILED", $path."/mask", i18n("Invalid Subnet Mask value"));
			return;
		}
		if (INET_validv4host($ip, $mask)==0)
		{
			set_result("FAILED", $path."/ipaddr", i18n("Invalid host address"));
			return;
		}

		$gw = query("gateway");
		TRACE_debug("FATLADY: INET_IPV4: gw=".$gw);
		if ($gw=="")
		{
			if ($needgw=="1" && $static=="1")
			{
				set_result("FAILED", $path."/gateway", i18n("No default gateway IP address"));
				return;
			}
		}
		else
		{
			if (INET_validv4host($gw, $mask) == 0)
			{
				set_result("FAILED", $path."/gateway", i18n("Invalid default gateway IP address"));
				return;
			}
			if (ipv4networkid($gw,$mask) != ipv4networkid($ip,$mask))
			{
				set_result("FAILED", $path."/gateway", i18n("The default gateway should be in the same network"));
				return;
			}
			if ( $gw == $ip )
			{
				set_result("FAILED", $path."/gateway", i18n("The IP address can not be equal to the gateway address"));
				return;
			}
		}
	}
	else if (query("dhcpplus/enable")!="")
	{
		/* User Name & Password */
		if (query("dhcpplus/enable")=="1" && query("dhcpplus/username")=="")
		{
			set_result("FAILED",$path."/dhcpplus/username",i18n("The user name can not be empty"));
			return;
		}
	}

	$cnt = query("dns/count");
	$i = 0;
	while ($i < $cnt)
	{
		$i++;
		$value = query("dns/entry:".$i);
		TRACE_debug("FATLADY: INET_IPV4: dns".$i."=".$value);
		if (INET_validv4addr($value)==0)
		{
			set_result("FAILED", $path."/dns/entry:".$i, i18n("Invalid DNS address"));
			return;
		}

		if ($static == "1")
		{
			if (ipv4networkid($value,$mask) == ipv4networkid($ip,$mask))
			{
				TRACE_debug("FATLADY: INET_IPV4: dns".$i."=".$value." is in the same network as IP:".$ip);
				if (INET_validv4host($value, $mask) == 0)
				{
					set_result("FAILED", $path."/dns/entry:".$i, i18n("Invalid DNS address"));
					return;
				}
				if ( $value == $ip )
				{
					set_result("FAILED", $path."/dns/entry:".$i, i18n("Invalid DNS address"));
					return;
				}
			}
		}

		if ($i > 1)
		{
			$j = $i - 1;
			$k = 0;
			while ($k < $j)
			{
				$k++;
				$dns = query("dns/entry:".$k);
				if($value == $dns)
				{
					set_result("FAILED", $path."/dns/entry:2", i18n("Secondary DNS server should not be the same as Primary DNS server."));
					return;
				}
			}
		}
	}

	$mtu = query("mtu");
	TRACE_debug("FATLADY: INET_IPV4: mtu=".$mtu);
	if ($mtu!="")
	{
		if (isdigit($mtu)=="0")
		{
			set_result("FAILED", $path."/mtu",
				i18n("The MTU value is invalid."));
			return;
		}
		if ($mtu<576)
		{
			set_result("FAILED", $path."/mtu",
				i18n("The MTU value is too small, the valid value is 576 ~ 1500."));
			return;
		}
		if ($mtu>1500)
		{
			set_result("FAILED", $path."/mtu",
				i18n("The MTU value is too large, the valid value is 576 ~ 1500."));
			return;
		}
	}

	set_result("OK","","");
}

TRACE_debug("FATLADY: INET: inetentry=[".$_GLOBALS["FATLADY_INET_ENTRY"]."]");
set_result("FAILED","","");
if ($_GLOBALS["FATLADY_INET_ENTRY"]=="") set_result("FAILED","","No XML document");
else check_ipv4($_GLOBALS["FATLADY_INET_ENTRY"]."/ipv4", $_GLOBALS["FATLADY_INET_NEED_GW"]);
?>
