<?/* vi: set sw=4 st=4: */
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/wifi.php";
include "/htdocs/phplib/trace.php";

function wds_setcfg($from, $to)
{
	$active = query($from."/active");
	if ($active != "")	set ($to."/active",	$active);
	set($to."/media/peermac",	query($from."/media/peermac"));
}
function phyinf_setcfg($prefix_phyinf)
{
	/* phyinf */
	anchor($prefix_phyinf);
	$uid = query("uid");
	$support_11n = WIFI_issupport11n($uid);
	$to = XNODE_getpathbytarget("", "phyinf", "uid", $uid, 0);
	if ($to == "") 
	{
		TRACE_error("wifi_setcfg: CAN NOT find the path of /phyinf/uid =".$uid);
		return;
	}
	set($to."/active",						query("active"));
	set($to."/wifi",						query("wifi"));
	set($to."/brinf",						query("brinf"));
	set($to."/schedule",					query("schedule"));
	$parent = query("media/parent");
	if ($parent=="")
	{
		$wlmode = query("media/wlmode");
		set($to."/media/wlmode",			$wlmode);
		set($to."/media/beacon",			query("media/beacon"));
		set($to."/media/fragthresh",		query("media/fragthresh"));
		set($to."/media/rtsthresh",			query("media/rtsthresh"));
		set($to."/media/ctsmode",			query("media/ctsmode"));
		set($to."/media/channel",			query("media/channel"));
		if ($support_11n == 1)
		{
			set($to."/media/dot11n/bandwidth",		query("media/dot11n/bandwidth"));
			set($to."/media/dot11n/centralchannel",	query("media/dot11n/centralchannel"));
			set($to."/media/dot11n/guardinterval",	query("media/dot11n/guardinterval"));
			if (strchr($wlmode,"n")!="")
			{
				set($to."/media/dot11n/mcs/auto",	query("media/dot11n/mcs/auto"));
				set($to."/media/dot11n/mcs/index",	query("media/dot11n/mcs/index"));
			}
			else
				set($to."/media/txrate",			query("media/txrate"));
		}
		else
		{
			set($to."/media/txrate",		query("media/txrate"));
		}
		set($to."/media/txpower",			query("media/txpower"));
		set($to."/media/preamble",			query("media/preamble"));
		set($to."/media/dtim",				query("media/dtim"));
		set($to."/media/wmm/enable",		query("media/wmm/enable"));
		if (query("media/wmm/acbk#")=="1")
		{
			set($to."/media/wmm/acbk/cwmin",	query("media/wmm/acbk/cwmin"));
			set($to."/media/wmm/acbk/cwmax",	query("media/wmm/acbk/cwmax"));
			set($to."/media/wmm/acbk/aifsn",	query("media/wmm/acbk/aifsn"));
			set($to."/media/wmm/acbk/txop",		query("media/wmm/acbk/txop"));
			set($to."/media/wmm/acbe/cwmin",	query("media/wmm/acbe/cwmin"));
			set($to."/media/wmm/acbe/cwmax",	query("media/wmm/acbe/cwmax"));
			set($to."/media/wmm/acbe/aifsn",	query("media/wmm/acbe/aifsn"));
			set($to."/media/wmm/acbe/txop",		query("media/wmm/acbe/txop"));
			set($to."/media/wmm/acvi/cwmin",	query("media/wmm/acvi/cwmin"));
			set($to."/media/wmm/acvi/cwmax",	query("media/wmm/acvi/cwmax"));
			set($to."/media/wmm/acvi/aifsn",	query("media/wmm/acvi/aifsn"));
			set($to."/media/wmm/acvi/txop",		query("media/wmm/acvi/txop"));
			set($to."/media/wmm/acvo/cwmin",	query("media/wmm/acvo/cwmin"));
			set($to."/media/wmm/acvo/cwmax",	query("media/wmm/acvo/cwmax"));
			set($to."/media/wmm/acvo/aifsn",	query("media/wmm/acvo/aifsn"));
			set($to."/media/wmm/acvo/txop",		query("media/wmm/acvo/txop"));
		}
	}
	//TRACE_debug("====== dump ".$to." ======\n".dump(0, $to)."====== end of dump ".$to." ======\n");
	
}
function wifi_profile_setcfg($from, $to)
{
	anchor($from);
	set($to."/opmode",						query("opmode"));
	set($to."/ssid",						query("ssid"));
	set($to."/ssidhidden",					query("ssidhidden"));
	$authtype = query("authtype");
	$encrtype = query("encrtype");
	set($to."/authtype",					$authtype);
	set($to."/encrtype",					$encrtype);
	if ($encrtype == "WEP")
	{
		$ascii	= query("nwkey/wep/ascii");
		$size	= query("nwkey/wep/size");
		$defkey	= query("nwkey/wep/defkey");
		$key	= query("nwkey/wep/key:".$defkey);
		if ($ascii == "" ||$size == "")
		{
			$len = strlen($key);
			if		($len == 5)		{$ascii = 1;	$size = 64;}
			else if ($len == 13)	{$ascii = 1;	$size = 128;}
			else if ($len == 10)	{$ascii = 0;	$size = 64;}
			else if ($len == 26)	{$ascii = 0;	$size = 128;}
		}

		set($to."/nwkey/wep/size",			$size);
		set($to."/nwkey/wep/ascii",			$ascii);
		set($to."/nwkey/wep/defkey",		$defkey);
		set($to."/nwkey/wep/key:1",			query("nwkey/wep/key:1"));
		set($to."/nwkey/wep/key:2",			query("nwkey/wep/key:2"));
		set($to."/nwkey/wep/key:3",			query("nwkey/wep/key:3"));
		set($to."/nwkey/wep/key:4",			query("nwkey/wep/key:4"));
	}
	else if ($authtype == "WPA" || $authtype == "WPA2" || $authtype == "WPA+2")
	{
		TRACE_error("wifi_setcfg: radius config.");
		set($to."/nwkey/eap/radius",		query("nwkey/eap/radius"));
		set($to."/nwkey/eap/port",			query("nwkey/eap/port"));
		set($to."/nwkey/eap/secret",		query("nwkey/eap/secret"));
	}
	else if ($authtype == "WAPI")
	{
		TRACE_error("wifi_setcfg: wapias config.");
		set($to."/nwkey/wapi/as",        query("nwkey/wapi/as"));
		set($to."/nwkey/wapi/port",      query("nwkey/wapi/port"));
	}
	else if ($authtype == "WPAPSK" || $authtype == "WPA2PSK" || $authtype == "WPA+2PSK" || $authtype == "WAPIPSK")
	{
		$passphrase = query("nwkey/psk/passphrase");
		$key		= query("nwkey/psk/key");
		$gtk		= query("nwkey/rekey/gtk");
		if ($passphrase == "")
		{
			if (strlen($key) == 64) $passphrase = 0;
			else					$passphrase = 1;
		}
		set($to."/nwkey/psk/passphrase",	$passphrase);
		set($to."/nwkey/psk/key",			$key);
		if ($gtk!="")
			set($to."/nwkey/rekey/gtk", 	$gtk);
	}
	set($to."/wps/enable",					query("wps/enable"));
	set($to."/wps/pin",						query("wps/pin"));
	set($to."/wps/configured",				query("wps/configured"));
	if(query("acl/policy") != "")
	{
	movc($from."/acl", $to."/acl");
	}
}

function wifi_setcfg($prefix)
{
	foreach($prefix."/phyinf")
	{
		$u = cut(query("uid"), 0, "-");
		if (query("type") == "wifi")
		{
			if ($u == "WLAN")
			{
				phyinf_setcfg($prefix."/phyinf:".$InDeX);
			}
			else if ($u == "WDS")
			{
				$from = $prefix."/phyinf:".$InDeX;
				$to = XNODE_getpathbytarget("", "phyinf", "uid", query("uid"), 0); 
				if ($to == "")
				{
					TRACE_error("wifi_setcfg: CAN NOT find the path of /phyinf/uid =".$wds_uid);
					return;
				}
				wds_setcfg($from, $to);
			}
			/* wifi */
			$uid = query($prefix."/phyinf:".$InDeX."/wifi");
			$from= XNODE_getpathbytarget($prefix."/wifi", "entry", "uid", $uid, 0);
			if ($from == "")
			{
				TRACE_error("wifi_setcfg: CAN NOT find the path of ".$prefix."/wifi/entry/uid =".$uid);
				return;
			}
			$to = XNODE_getpathbytarget("/wifi", "entry", "uid", $uid, 0);
			if ($to == "")
			{
				TRACE_error("wifi_setcfg: CAN NOT find the path of /wifi/entry/uid =".$uid);
				return;
			}
			wifi_profile_setcfg($from, $to);
		}
	}
	/* mac clone for apc only */
	$p1 = XNODE_getpathbytarget($prefix, "phyinf", "uid", "WLAN-1", 0);
	if (query($p1."/macclone/type")!="")
	{
		set("/phyinf:2/macclone/type",	query($p1."/macclone/type"));
		set("/phyinf:2/macclone/macaddr",	query($p1."/macclone/macaddr"));
	}
}
?>
