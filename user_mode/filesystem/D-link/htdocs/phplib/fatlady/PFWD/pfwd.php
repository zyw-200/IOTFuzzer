<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/inet.php";
include "/htdocs/phplib/inf.php";
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"] = $result;
	$_GLOBALS["FATLADY_node"]   = $node;
	$_GLOBALS["FATLADY_message"]= $message;
}

function isport($no)
{
	if (isdigit($no)=="0")  return "-1";
	if ($no<1 || $no>65535) return "-1";
	return "0";
}

function check_portlist($port)
{
	if (cut_count($port,":") > 1)
	{
		$start = cut($port,0,":");
		$end = cut($port,1,":");
		if (isport($start)=="-1" || isport($end)=="-1")
			return "-1";
		else if ($start > $end)
			return "-1";
		else
			return "0";
	}
	else
	{
		return isport($port);
	}
}

function check_pfwd_config($prefix, $nat, $svc)
{
	set_result("FAILED","","");
	$rlt = "0";

	$db = XNODE_getpathbytarget("/nat", "entry", "uid", $nat, 0);
	if ($db=="")	set_result("FAILED", "", "Can't Find ".$db);

	if		($svc=="PFWD")	{ $db = $db."/portforward";		$base = $prefix."/nat/entry/portforward";}
	else if ($svc=="VSVR")	{ $db = $db."/virtualserver";	$base = $prefix."/nat/entry/virtualserver";}

	if (query($base."/entry#") > query($db."/max"))
	{
		set_result("FAILED", $base."/max", i18n("The rules exceed maximum."));
		$rlt = "-1";
		return;
	}
	

	foreach($base."/entry")
	{
		$port_list_flag = false;
		if (query("description")=="")
		{
			set_result("FAILED",$base."/entry:".$InDeX."/description",i18n("Please input the rule name."));
			$rlt = "-1";
			break;
		}
		
		if(query("tport_str")!="" || query("uport_str")!="")
		{
			$port_list_flag = true;
			$pt_list = query("tport_str");
			$cnt = cut_count($pt_list, ",");
			$idx = 0;
			while ($idx < $cnt && query("tport_str")!="")
			{
				if (check_portlist(cut($pt_list,$idx,","))=="-1")
				{
					set_result("FAILED",$base."/entry:".$InDeX."/tport_str",i18n("The port range of firewall is invalid."));
					$rlt = "-1";
					break;
				}
				$idx++;
			}
			$pt_list = query("uport_str");
			$cnt = cut_count($pt_list, ",");
			$idx = 0;
			while ($idx < $cnt && query("uport_str")!="")
			{
				if (check_portlist(cut($pt_list,$idx,","))=="-1")
				{
					set_result("FAILED",$base."/entry:".$InDeX."/uport_str",i18n("The port range of firewall is invalid."));
					$rlt = "-1";
					break;
				}
				$idx++;
			}
		}
		else
		{
			if (query("protocol")=="")
			{
				set_result("FAILED",$base."/entry:".$InDeX."/protocol",i18n("Please select the protocol."));
				$rlt = "-1";
				break;
			}
			else if (query("protocol")!="TCP"&&query("protocol")!="UDP"&&query("protocol")!="TCP+UDP")
			{
				set_result("FAILED",$base."/entry:".$InDeX."/protocol",i18n("The protocol is invalid."));
				$rlt = "-1";
				break;
			}
	
			$ex_start	= query("external/start");
			$ex_end		= query("external/end");
			if ($ex_end=="") $ex_end = $ex_start;
			if ($ex_start=="")
			{
				set_result("FAILED",$base."/entry:".$InDeX."/external/start",i18n("Please input the public port."));
				$rlt = "-1";
				break;
			}
	
			if (isdigit($ex_start)=="0" || isdigit($ex_end)=="0")
			{
				set_result("FAILED",$base."/entry:".$InDeX."/external/start",i18n("The public port is invalid.")
							." ".i18n("Wrong value."));
				$rlt = "-1";
				break;
			}
	
			/* convert to integer */
			$ex_start += 0;
			$ex_end += 0;
			set("external/start", $ex_start);
			set("external/end", $ex_end);
	
			if ($ex_start > $ex_end)
			{
				set_result("FAILED",$base."/entry:".$InDeX."/external/start",i18n("The public port is invalid.")
							." ".i18n("End port should be bigger than start port."));
				$rlt = "-1";
				break;
			}
			if ($ex_start<"1"||$ex_start>"65535"||$ex_end<"1"||$ex_end>"65535")
			{
				set_result("FAILED",$base."/entry:".$InDeX."/external/start",i18n("The public port is invalid.")
							." ".i18n("The port is out of the boundary."));
				$rlt = "-1";
				break;
			}
		}
		$inet	= INF_getinfinfo(query("internal/inf"), "inet");
		$mask	= INET_getinetinfo($inet, "ipv4/mask");
		$hostid	= query("internal/hostid");
		if ($hostid=="")
		{
			set_result("FAILED",$base."/entry:".$InDeX."/internal/hostid",i18n("The IP Address could not be blank."));
			$rlt = "-1";
			break;
		}
		if (isdigit($hostid)=="0")
		{
			set_result("FAILED",$base."/entry:".$InDeX."/internal/hostid",i18n("The input hostid is invalid."));
			$rlt = "-1";
			break;
		}
		if ($hostid<1 || $hostid>=ipv4maxhost($mask))
		{
			set_result("FAILED",$base."/entry:".$InDeX."/internal/hostid",i18n("The input hostid is out of the boundary."));
			$rlt = "-1";
			break;
		}
		
		$path_inf_lan1 = XNODE_getpathbytarget("", "inf", "uid", "LAN-1", 0);
		$path_lan1_inet = XNODE_getpathbytarget("/inet", "entry", "uid", query($path_inf_lan1."/inet"), 0);
		$hostid_lan1 = ipv4hostid(query($path_lan1_inet."/ipv4/ipaddr"), query($path_lan1_inet."/ipv4/mask"));
		if ($hostid == $hostid_lan1)
		{
			set_result("FAILED",$base."/entry:".$InDeX."/internal/hostid",i18n("The IP Address could not be the same as LAN IP Address."));
			$rlt = "-1";
			break;
		}

		if($port_list_flag==false)
		{
			$in_start	= query("internal/start");
			if ($in_start=="") $in_start = $ex_start;
			$in_end		= $in_start + $ex_end - $ex_start;
			if ($in_start=="")
			{
				
				set_result("FAILED",$base."/entry:".$InDeX."/internal/start",i18n("Please input the private port."));
				$rlt = "-1";
				break;
			}
	
			if (isdigit($in_start)=="0")
			{
				set_result("FAILED",$base."/entry:".$InDeX."/internal/start",i18n("The private port is invalid.")
							." ".i18n("Wrong value."));
				$rlt = "-1";
				break;
			}
	
			/* convert to integer */
			$in_start += 0;
			set("internal/start", $in_start);
	
			if ($in_start<"1"||$in_start>"65535"||$in_end>"65535")
			{
				set_result("FAILED",$base."/entry:".$InDeX."/internal/start",i18n("The private port is invalid.")
							." ".i18n("The port is out of the boundary."));
				$rlt = "-1";
				break;
			}
		}
	}

	if ($rlt=="0")
	{
		set($prefix."/valid", "1");
		set_result("OK", "", "");
	}
}

function fatlady_pfwd($prefix, $nat, $svc)
{
	set_result("FAILED","","");
	if ($prefix=="")	set_result("FAILED","","No XML document");
	else				check_pfwd_config($prefix, $nat, $svc);
}

?>
