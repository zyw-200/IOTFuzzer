<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inet.php";
include "/htdocs/phplib/wifi.php";
include "/htdocs/phplib/getchlist.php";

//++++ hendry add for dfs 
function execute_cmd($cmd)
{
	fwrite("w","/var/run/exec.sh",$cmd);
	event("EXECUTE");
}

function setToRuntimeNode($blocked_channel, $timeleft)
{
	/* find blocked channel if already in runtime node */
	$blocked_chn_total = query("/runtime/dfs/blocked/entry#");
	/* if blocked channel exist before, use the old index. */
	$index = 1;
	while($index <= $blocked_chn_total)
	{
		if($blocked_chn_total == 0) {break;}
		$ch = query("/runtime/dfs/blocked/entry:".$index."/channel");
		if($ch == $blocked_channel)
		{
			break;	
		}
		$index++;
	}
	set("/runtime/dfs/blocked/entry:".$index."/channel",$blocked_channel);
	execute_cmd("xmldbc -t \"dfs-".$blocked_channel.":".$timeleft.":xmldbc -X /runtime/dfs/blocked/entry:".$index."\"");
	//execute_cmd("xmldbc -t \"dfs-".$blocked_channel.":5:xmldbc -X /runtime/dfs/blocked/entry:".$index."\"");
}


function valid_mac($validMac)
{
    if ($validMac=="") return 0;

    $num = cut_count($validMac, ":");
    if ($num != 6) return 0;
    $num--;
    while ($num >= 0)
    {
        $tmpMac = cut($validMac, $num, ":");
        if (isxdigit($tmpMac) == 0) return 0;
		if (strlen($tmpMac) > 2) return 0;
        $num--;
    }
	$validMac = tolower($validMac);
	if ($validMac=="00:00:00:00:00:00" || $validMac=="ff:ff:ff:ff:ff:ff") return 0;
    return 1;
}
function revise_mac($mac)
{
	if ($mac=="") return "";
	$num = cut_count($mac, ":");
	$num--;
	while ($num >= 0)
	{
		$tmp = cut($mac, $num, ":");
		if (strlen($tmp) == 1) $tmp = "0".$tmp;
		$ret_mac = $tmp.$delimiter.$ret_mac;
		$delimiter=":";
		$num--;
	}
	return $ret_mac;
}
function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"]	= $result;
	$_GLOBALS["FATLADY_node"]	= $node;
	$_GLOBALS["FATLADY_message"]= $message;
}

function check_authtype_encrtype($path, $authtype, $encrtype)
{
	$err = 0;
	if ($authtype == "OPEN" || $authtype == "SHARED")
	{
		if ($encrtype != "NONE" && $encrtype != "WEP") $err++;
	}
	else if ($authtype == "WPA"		|| $authtype == "WPA2"		|| $authtype == "WPA+2" ||
			 $authtype == "WPAPSK"	|| $authtype == "WPA2PSK"	|| $authtype == "WPA+2PSK")
	{
		if ($encrtype != "TKIP" && $encrtype != "AES" && $encrtype != "TKIP+AES") $err++;
	}
	else if ($authtype == "WAPI"	|| $authtype == "WAPIPSK")
	{
		if ($encrtype != "SMS4") $err++;
	}
	else /* invalid authtype */
	{
		set_result("FAILED", $path."/authtype", "Invalid authentication type.");
		return 0;
	}

	if ($err > 0)
	{
		set_result("FAILED", $path."/encrtype", "Invalid encryption type.");
		return 0;
	}

	return 1;
}

function check_wep($p)
{
	$p = $p."/nwkey";
	$size	= query($p."/wep/size");
	$ascii	= query($p."/wep/ascii");
	$idx	= query($p."/wep/defkey");

	$err_s	= 0;	$err_a	= 0;	$err_i	= 0;
	$err_kl	= 0;	$err_km	= 0;

	$i = 0;
	while ($i < 1)
	{
		$i++;
		$len = strlen(query($p."/wep/key:".$idx));
		if ($idx < 1 || $idx > 4) {$err_i++; break;}
		if ($size == "")
		{
			if		($len == "5"  || $len == "10")	$size = 64;
			else if ($len == "13" || $len == "26")	$size = 128;
			else	{$err_s++; break;}
		}
		if ($ascii == "")
		{
			if		($len == 5  || $len == 13) $ascii = 1;
			else if ($len == 10 || $len == 26) $ascii = 0;
			else	{$err_s++; break;}
		}
		if ($ascii != 0 && $ascii != 1) {$err_a++; break;}
		if ($ascii == 0)
		{
			if		($size == 64)	$len = 10;
			else if ($size == 128)	$len = 26;
			else					{$err_s++; break;}
		}
		else if	($ascii == 1)
		{
			if		($size == 64)	$len = 5;
			else if ($size == 128)	$len = 13;
			else					{$err_s++; break;}
		}

		foreach ($p."/wep/key")
		{
			if ($VaLuE == "")
			{
				if ($InDeX != $idx)		 continue;
				else					{$err_kl = $InDeX; break;}
			}
			if (strlen($VaLuE) != $len)	{$err_kl = $InDeX; break;}
			if ($ascii==1)
			{
				if (isprint($VaLuE)!=1)	{$err_km = $InDeX; break;}
			}
			else
			{
				if (isxdigit($VaLuE)!=1){$err_km = $InDeX; break;}
			}
		}
	}

	if ($err_s > 0)
	{
		set_result("FAILED", $p."/wep/size", i18n("The size of WEP key should be 64 or 128.")." "
											.i18n("And the length of WEP key should be 5 or 10 or 13 or 26."));
		return 0;
	}
	if ($err_a > 0)
	{
		set_result("FAILED", $p."/wep/ascii", i18n("The material of WEP key should be ASCII or HEX."));
		return 0;
	}
	if ($err_i > 0)
	{
		set_result("FAILED", $p."/wep/defkey", i18n("The default WEP key index should be between 1 to 4."));
		return 0;
	}
	if ($err_kl > 0)
	{
		set_result("FAILED", $p."/wep/key:".$err_kl, i18n("The length of WEP key $1 should be $2.",$err_kl, $len));
		return 0;
	}
	if ($err_km > 0)
	{
		if ($ascii == 1)	$m = "ASCII";
		else				$m = "HEX";
		set_result("FAILED", $p."/wep/key:".$err_km, i18n("The material of WEP key $1 should be $2.",$err_km, $m));
		return 0;
	}

	return 1;
}
function check_radius($p)
{
	$p = $p."/nwkey";
	if (query($p."/eap#")==0)
	{
		set_result("FAILED", $p."/eap", "CAN NOT find the EAP nodes!");
		return 0;
	}
	if (INET_validv4addr(query($p."/eap/radius"))!=1)
	{
		set_result("FAILED", $p."/eap/radius", i18n("The IP address of RADIUS is invalid."));
		return 0;
	}
	$value = query($p."/eap/port");
	if ($value > 65535 || $value < 1)
	{
		set_result("FAILED", $p."/eap/port", i18n("The range of port is 1~65535. (recommend port : 1812)."));
		return 0;
	}
	$value = query($p."/eap/secret");
	$len = strlen($value);
	if ($len > 64 || $len < 1)
	{
		set_result("FAILED", $p."/eap/secret", i18n("The length of secret should be between 1 to 64."));
		return 0;
	}
	if (isprint($value)!=1)
	{
		set_result("FAILED", $p."/eap/secret", i18n("The material of secret should be ASCII."));
		return 0;
	}
	return 1;
}
function check_wapias($p)
{
	$p = $p."/nwkey";
	if (query($p."/wapi#")==0)
	{
		set_result("FAILED", $p."/wapi", "CAN NOT find the WAPI nodes!");
		return 0;
	}
	if (INET_validv4addr(query($p."/wapi/as"))!=1)
	{
		set_result("FAILED", $p."/wapi/as", i18n("The IP address of Auth Server is invalid."));
		return 0;
	}
	$value = query($p."/wapi/port");
	if ($value > 65535 || $value < 1)
	{
		set_result("FAILED", $p."/wapi/port", i18n("The range of port is 1~65535."));
		return 0;
	}
	return 1;
}

function check_psk($p)
{
	$p = $p."/nwkey";
	$type = query($p."/psk/passphrase");
	$key = query($p."/psk/key");
	$gtk = query($p."/rekey/gtk");
	$len = strlen($key);
	if ($type == "")
	{
		if ($len >= 8 && $len <= 63)	$type = 1;
		else if ($len == 64)			$type = 0;
		else
		{
			set_result("FAILED", $p."/psk/key", i18n("The length of PSK / Passphrase should be between 8 to 64."));
			return 0;
		}
	}
	if ($type==0)
	{
		if ($len!=64 || isxdigit($key)!=1)
		{
			set_result("FAILED", $p."/psk/key", i18n("The PSK should be 64 HEX."));
			return 0;
		}
	}
	else if ($len < 8 || $len > 63 || isprint($key)!=1)
	{
		set_result("FAILED", $p."/psk/key", i18n("The PSK should be 8~63 ASCII."));
		return 0;
	}
	if ($gtk!="")
	{
		if (isdigit($gtk)!=1)
		{
			set_result("FAILED", $p."/rekey/gtk", i18n("The value of rekey interval should be digit."));
			return 0;
		}
	}
	return 1;
}
function check_pin($pin)
{
	if (strlen($pin)!=8) return 0;
	$i = 0;	$pow = 3; $sum = 0;
	while($i < 8)
	{
		$sum = $pow * substr($pin, $i, 1) + $sum;
		if ($pow == 3)	$pow = 1;
		else			$pow = 3;
		$i++;
	}
	$sum = $sum % 10;
	if ($sum == 0)	return 1;
	else			return 0;
}
function check_wps($p)
{
	$value = query($p."/wps/enable");
	if ($value != 1) set($p."/wps/enable", 0);
	$pin = query($p."/wps/pin");
	if ($pin != "" && check_pin($pin)!=1)
	{
		set_result("FAILED", $p."/wps/pin", i18n("The WPS pin code is invalid."));
		return 0;
	}
	$value = query($p."/wps/configured");
	if ($value != 1) set($p."/wps/configured", 0);
	return 1;
}
function check_acl($p)
{
	$value = query($p."/acl/policy");
	if ($value!="DISABLED" && $value!="ACCEPT" && $value!="DROP")
	{
		set_result("FAILED", $p."/acl/policy", i18n("The policy of ACL is invalid."));
		return 0;
	}
	$max = query($p."/acl/max");
	$cnt = query($p."/acl/count");
	$num = query($p."/acl/entry#");
	if ($cnt > $max)
	{
		set_result("FAILED", $p."/acl/count", i18n("The ACL rules are full."));
		return 0;
	}
	/* delete the extra rule. */
	while ($num > $cnt)
	{
		del($p."/acl/entry:".$num);
		$num = query($p."/acl/entry#");
	}
	$seqno = query($p."/acl/seqno");
	foreach ($p."/acl/entry")
	{
		$mac = query("mac");
		if (valid_mac($mac)==0)
		{
			set_result("FAILED", $p."/acl/entry:".$InDeX, i18n("Invalid MAC address value."));
			return 0;
		}
		/* Convert to lower case */
		$mac = tolower($mac);
		$mac = revise_mac($mac);
		set("mac", $mac);

		$uid = query("uid");
		/* Check empty UID */
		if ($uid == "")
		{
			$uid = "ACL-".$seqno;
			set("uid", $uid);
			$seqno++;
		}
		/* Check duplicated UID */
		if ($$uid == "1")
		{
			set_result("FAILED", $p.":".$InDeX."/uid", "Duplicated UID - ".$uid);
	       	return;
    	}
    	$$uid = "1";
	}
	/* Check duplicate mac after all MACs are valid & in lower case.*/
	foreach ($p."/acl/entry")
	{
		$m1 = query("mac");
		$i2 = $InDeX;
		//TRACE_debug("acl[".$i2."]");
		while ($i2 < $cnt)
		{
			$i2++;
			$m2 = query($p."/acl/entry:".$i2."/mac");
			$m2 = tolower($m2);
			//TRACE_debug("acl:".$i2."-".$m2);
			if ($m1 == $m2) {$err++; break;}
		}
		if ($err > 0)
		{
			set_result("FAILED", $p."/acl/entry:".$InDeX, i18n("Duplicate MAC address."));
			return 0;
		}
	}
	set($p."/acl/seqno", $seqno);
	return 1;
}

function check_dfs($current_channel)
{
	/*1. Update new blocked channel to runtime nodes */
	$blockch_list = fread("", "/proc/dfs_blockch");
	//format is : "100,960;122,156;" --> channel 100, remaining time is 960 seconds
	//								 --> channel 122, remaining time is 156 seconds
	$ttl_block_chn = cut_count($blockch_list, ";")-1;
	$i = 0;
	while($i < $ttl_block_chn)
	{
		//assume that blocked channel can be more than one channel.
		$ch_field = cut($blockch_list, $i, ';');	//i mean each "100,960;" represent 1 field 
		$ch = cut ($ch_field, 0, ',');
		$remaining_time = cut ($ch_field, 1, ',');
		setToRuntimeNode($ch, $remaining_time);
		$i++;
	}
	

	/*2. Check if blocked dfs channels matched our current channel */
	$ct=1;
	$ttl_blocked_chn = query("/runtime/dfs/blocked/entry#");	
	while($ct <= $ttl_blocked_chn)
	{
		if($ttl_blocked_chn == 0) {break;}
		$blck_chnl = query("/runtime/dfs/blocked/entry:".$ct."/channel");
		if($current_channel == $blck_chnl) return 0;
		$ct++;
	}
	return 1;
}

function check_media($p)
{
	$support_11n = WIFI_issupport11n(query($p."/uid"));

	/* re-assign path at $p./media */
	$p = $p."/media";
	/* wlmode */
	$wlmode	= query($p."/wlmode");
	$meet	= 0;
	if ($wlmode == "b" || $wlmode == "g" || $wlmode == "bg" || $wlmode == "a") $meet++;
	else if($support_11n == 1)
	{
		if ($wlmode == "n" || $wlmode == "bn" || $wlmode == "gn" || $wlmode == "bgn" ||$wlmode == "an") $meet++;
	}
	if ($meet == 0)
	{
		set_result("FAILED", $p."/wlmode", "The wireless mode [".$wlmode."] is invalid.");
		return 0;
	}
	/* beacon */
	$v = query($p."/beacon");
	if ($v < 20 || $v > 1000)
	{
		set_result("FAILED", $p."/beacon", i18n("Invalid value of Beacon. The range should be between 20 to 1000."));
		return 0;
	}
	/* fragthresh */
	$v = query($p."/fragthresh");
	$mod = $v%2;
	if ($v < 1500 || $v > 2346 || $mod!=0)
	{
		set_result("FAILED", $p."/fragthresh", i18n("Invalid value of Fragmentation Threshold. The range should be between 1500 to 2346, and even number only."));
		return 0;
	}
	/* rtsthresh */
	$v = query($p."/rtsthresh");
	if ($v < 256 || $v > 2346)
	{
		set_result("FAILED", $p."/rtsthresh", i18n("Invalid value of RTS Threshold. The range should be between 256 to 2346."));
		return 0;
	}
	/* ctsmode */
	/* dtim */
	$v = query($p."/dtim");
	if (isdigit($v)!=1 || $v < 1 || $v > 255)
	{
		set_result("FAILED", $p."/dtim", i18n("Invalid value of DTIM. The range should be between 1 to 255."));
		return 0;
	}
	/* channel */
	$v = query($p."/channel");
	if ($v != 0)
	{
		if (query($p."/freq")=="5")	
		{
			$band = "a";
			//hendry, we check for dfs blocked channel if A band
			if (check_dfs($v)!=1) 
			{
				set_result("FAILED", $p."/channel", i18n("Selected channel is blocked. Please select other channel !"));	
				return 0;
			}
		}
		else						$band = "g";
		$clist = WIFI_getchannellist($band);
		$count = cut_count($clist,",");
		$i = 0; $meet = 0;
		while($i < $count)
		{
			if ($v == cut($clist, $i, ","))
			{
				$meet++;
				break;
			}
			$i++;
		}
		if ($meet == 0)
		{
			set_result("FAILED", $p."/channel", "Invalid Channel:".$v.". The valid channel list is ".$clist.".");
			return 0;
		}
	}
	/* tx rate */
	$meet = 0;
	$v = query($p."/txrate");
	if ($wlmode == "n" || $wlmode == "bn" || $wlmode == "gn" || $wlmode == "bgn")
	{
		/* dot11n/mcs */
		$msc = query($p."/mcs/auto");
		if ($mcs != 1)
		{
			$idx = query($p."/mcs/index");
			if ($idx >= 0 && $idx <= 15)
			{
				$meet++;
				set($p."/mcs/auto", 0);
			}
		}
	}
	else
	{
		if ($v == "auto") $meet++;
		else
		{
			if ($wlmode == "bg" || $wlmode == "g")
			{
				if ($v=="1" || $v=="2" || $v=="5.5" || $v=="11"
					|| $v=="6" || $v=="9" || $v=="12" || $v=="18" || $v=="24" || $v=="36" || $v=="48" || $v=="54") $meet++;
			}
			else if($wlmode == "b"){
			 	if ($v=="1" || $v=="2" || $v=="5.5" || $v=="11") $meet++;
			}
			else
				$meet=0;
		}
	}
	if ($meet == 0)
	{
		set_result("FAILED", $p."/txrate", "Invalid Tx Rate.");
		return 0;
	}
	/* tx power */
	$v = query($p."/txpower");
	if ($v!="100" && $v!="50" && $v!="25" && $v!="12.5")
	{
		set_result("FAILED", $p."/txpower", "Invalid Tx power:".$v);
		return 0;
	}
	/* preamble */
	$v = query($p."/preamble");
	if ($v!="short" && $v!="long")
	{
		set_result("FAILED", $p."/preamble", "Invalid Preamble:".$v);
		return 0;
	}
	/* dot11n*/
	if ($support_11n != 1)
	{
		del($p."/media/dot11n");
	}
	else
	{
		/* dot11n/bandwidth */
		$v = query($p."/dot11n/bandwidth");
		if ($v!="20" && $v!="20+40")	set($p."/dot11n/bandwidth", "20");
		/* dot11n/guardinterval */
		$v = query($p."/dot11n/guardinterval");
		if ($v!="400" && $v!="800")		set($p."/dot11n/guardinterval", "400");
	}
	/* wmm */
	$v = query($p."/wmm/enable");
	if ($v!=0 || $v!=1)
	{
		if ($v >= 1)set($p."/wmm/enable", 1);
		else		set($p."/wmm/enable", 0);
	}

	return 1;
}
function check_wds($p)
{
	foreach ($p)
	{
		if (cut(query($p.":".$InDeX."/uid"), 0, "-") == "WDS")
		{
			$mac = query($p.":".$InDeX."/media/peermac");
			if($mac != "" && valid_mac($mac) != 1)
			{
				set_result("FAILED", $p.":".$InDeX."/media/peermac", i18n("Invalid MAC address."));
				return 0;
			}
		}
	}
	return 1;
}



function fatlady_wifi($prefix, $phyinf)
{
	/********************* phyinf ***********************/
	$base_phyinf = XNODE_getpathbytarget($prefix, "phyinf", "uid", $phyinf, 0);
	if ($base_phyinf=="")
	{
		/* internal error, no i18n(). */
		set_result("FAILED", $base_phyinf."/uid", "phyinf=[".$phyinf."] not exist!");
		return;
	}
	/* media */
	if (query($base_phyinf."/media/parent")=="" && check_media($base_phyinf)!=1) return;
	if (check_wds($base_phyinf)!=1) return;
	/********************* wifi ***********************/
	$wifi_uid = query($base_phyinf."/wifi");
	$p = XNODE_getpathbytarget($prefix."/wifi", "entry", "uid", $wifi_uid);
	if ($p == "")
	{
		/* internal error, no i18n(). */
		set_result("FAILED", $prefix."/wifi/uid", "wifi [".$wifi_uid."] not exist!");
		return;
	}
	/* opmode */
	$value = query($p."/opmode");
	if ($value!="STA" && $value!="AP" && $value!="WDS" && $value!="REPEATER" && $value!="APNF")
	{
		set_result("FAILED", $p."/opmode", "The operation mode of this WIFI is invalid.");
		return;
	}
	/* ssid */
	$value = query($p."/ssid");
	$len = strlen($value);
	if ($len > 32 || $len <= 0)
	{
		set_result("FAILED", $p."/ssid", i18n("The length of SSID should be 1~32."));
		return;
	}
	if (isprint($value)!=1)
	{
		set_result("FAILED", $p."/ssid", i18n("The SSID should be 1~32 characters."));
		return;
	}
	/* ssidhidden */
	$value = query($p."/ssidhidden");
	if ($value!=1 && $value!=0)
	{
		set_result("FAILED", $p."/ssidhidden", i18n("The value of SSID hidden should be 0 or 1."));
		return;
	}
	/* authtype & encryption */
	$authtype = query($p."/authtype");
	$encrtype = query($p."/encrtype");
	if (check_authtype_encrtype($p, $authtype, $encrtype) != 1) return;
	/* nwkey */
	if ($authtype == "OPEN" || $authtype == "SHARED")
	{
		if ($encrtype == "WEP" && check_wep($p)!=1) return;
	}
	else if ($authtype == "WPA" || $authtype == "WPA2" || $authtype == "WPA+2")
	{
		if (check_radius($p)!=1) return;
	}
	else if ($authtype == "WAPI")
	{
		if (check_wapias($p)!=1) return;
	}
	else if ($authtype == "WPAPSK" || $authtype == "WPA2PSK" || $authtype == "WPA+2PSK" || $authtype == "WAPIPSK")
	{
		if (check_psk($p)!=1) return;
	}
	/* wps */
	if (check_wps($p)!=1) return;
	/* ACL */
	if (check_acl($p)!=1) return;

	$value = query($p."/opmode");
	if ($value=="STA")
	{
		$p1 = XNODE_getpathbytarget($prefix, "phyinf", "uid", "WLAN-1");
		set($p."/macclone/macaddr", tolower(query($p1."/macclone/macaddr")));
		$clonetype		=	query($p1."/macclone/type");
		$clonemacaddr	=	query($p1."/macclone/macaddr");
		//TRACE_debug("FATLADY: MAC Clone: Type=".$clonetype." MAC Addr=".$clonemacaddr);
		if($clonetype!="DISABLED" && $clonetype!="AUTO" && $clonetype!="MANUAL")
		{
			set_result("FAILED", $p."/macclone/type", i18n("Unknown clone type."));
			return;
		}
		else if($clonetype=="MANUAL")
		{
			if(valid_mac($clonemacaddr)==0)
			{
				set_result("FAILED", $p."/macclone/macaddr", i18n("Invalid MAC address."));
				return;
			}
		}
	}

	//TRACE_debug("====== dump ".$p." ======\n".dump(0, $p)."====== end of dump ".$p." ======\n");
	set($prefix."/valid", 1);
	set_result("OK","","");
}
function fatlady_runtime_wps($prefix, $phyinf)
{
	$base_phyinf = XNODE_getpathbytarget($prefix."/runtime", "phyinf", "uid", $phyinf, 0);
	if ($base_phyinf=="")
	{
		/* internal error, no i18n(). */
		set_result("FAILED", $prefix."/runtime/phyinf/uid", "phyinf=[".$phyinf_uid."] not exist!");
		return;
	}
	$method_p = $base_phyinf."/media/wps/enrollee/method";
	$method = query($method_p);
	$pin_p = $base_phyinf."/media/wps/enrollee/pin";
	$state_p = $base_phyinf."/media/wps/enrollee/state";
	if ($method == "pbc")
	{
		set($pin_p, "00000000");
	}
	else if ($method == "pin")
	{
		if (check_pin(query($pin_p))!=1)
		{
			set_result("FAILED", $pin_p, i18n("The WPS pin code is invalid."));
			return;
		}
	}
	else
	{
		set_result("FAILED", $method_p, "Invalid WPS method!");
		return;
	}
	set($state_p, "");

	set($prefix."/valid", 1);
	set_result("OK","","");
}
?>
