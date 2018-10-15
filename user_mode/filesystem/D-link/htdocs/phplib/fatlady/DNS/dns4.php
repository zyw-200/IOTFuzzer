<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inet.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"] = $result;
	$_GLOBALS["FATLADY_node"]   = $node;
	$_GLOBALS["FATLADY_message"]= $message;
	return $result;
}

function check_filter($path)
{
	$cnt = query($path."/count");
	$num = query($path."/entry#");
	while ($num > $cnt)
	{
		del($path."/entry:".$num);
		$num = query($path."/entry#");
	}

	foreach ($path."/entry")
	{
		if (query("enable")!="1") set("enable", "0");
		$domain = query("string");
		if ($domain == "")
			return set_result(
					"FAILED",
					$path."/entry:".$InDeX."/string",
					i18n("Please input the domain name.")
					);

		if (charcodeat($domain, 0)=='.') $test = "www".$domain;
		else $test = $domain;
		if (isdomain($test) != 1)
			return set_result(
					"FAILED",
					$path."/entry:".$InDeX."/string",
					i18n("Invalid domain name.")
					);

		$i = 1;
		while ($i < $InDeX)
		{
			if (query($path."/entry:".$i."/string")==$domain)
				return set_result(
					"FAILED",
					$path."/entry:".$InDeX."/string",
					i18n("Duplicated domain name")
					);
			$i++;
		}
	}

	return "OK";
}

function check_dns4($path, $uid)
{
	$base = XNODE_getpathbytarget($path."/dns4", "entry", "uid", $uid, 0);
	if ($base == "")
		return set_result(
					"FAILED",
					$path."/inf/dns4",
					"Invalid profile - ".$uid
					);

	$cnt = query($base."/count");
	$num = query($base."/num");
	while ($num > $cnt)
	{
		del($base."/entry:".$num);
		$num = query($base."/entry#");
	}

	foreach ($base."/entry")
	{
		/* Check the UID */
		$uid = query("uid");
		if ($$uid == 1)
			return set_result(
					"FAILED",
					$base."/entry:".$InDeX."/uid",
					"Duplicated UID - ".$uid
					);

		$$uid = 1;
		/* Check type */
		$type = query("type");
		if ($type == "inf")
		{
			$inf = query("inf");
			$infp = XNODE_getpathbytarget("", "inf", "uid", $inf, 0);
			if ($infp == "")
				return set_result(
					"FAILED",
					$base."/entry:".$InDeX."/inf",
					"Invalid interface - ".$inf
					);
		}
		else if ($type=="static")
		{
			$ipaddr = query("ipaddr");
			if (INET_validv4addr($ipaddr)==0)
				return set_result(
					"FAILED",
					$base."/entry:".$InDeX."/ipaddr",
					"Invalid IP address - ".$ipaddr
					);
		}
		else if ($type!="local")
		{
			return set_result(
					"FAILED",
					$base."/entry:".$InDeX."/type",
					"Unsupported type - ".$type
					);
		}

		/* Check filter */
		if (check_filter($base."/entry:".$InDeX."/filter")!="OK")
			return "FAILED";

	}

	return "OK";
}

function fatlady_dns4($prefix, $inf)
{	
	set_result("FAILED", "", "");

	$infp = XNODE_getpathbytarget($prefix, "inf", "uid", $inf, 0);
	if ($infp == "") return;
	$ProfileUID = query($infp."/dns4");

	if (check_dns4($prefix, $ProfileUID)=="OK")
	{
		set($prefix."/valid", 1);
		set_result("OK","","");
	}
}
?>
