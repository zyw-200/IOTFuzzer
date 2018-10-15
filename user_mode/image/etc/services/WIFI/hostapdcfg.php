<?/* vi: set sw=4 ts=4: */
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/upnp.php";


function sync_wps_configured()
{
	/*if dual band, then the wps configured value of A band and G band should always be the same .*/
	$wps_configured = 0;
	foreach("/wifi/entry")
	{
		if (query("wps/configured")=="1")
		{
			$wps_configured = 1;
			//TRACE_error("sync_wps_configured query configured is 1\n");
			break;
		}
	}
	
	if($wps_configured == 1)
	{
		foreach("/wifi/entry")
		{
			set("wps/configured", "1");
			//TRACE_error("sync_wps_configured wps configured = 1\n");
		}
	}
}

$phy	= XNODE_getpathbytarget("", "phyinf", "uid", $PHY_UID, 0);
$phyrp	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", $PHY_UID, 0);
$wifi	= XNODE_getpathbytarget("/wifi", "entry", "uid", query($phy."/wifi"), 0);
$br		= XNODE_getpathbytarget("", "inf", "uid", query($phyrp."/brinf"),0);
$brrp	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($br."/phyinf"), 0);

anchor($phyrp);
$HAPD_inf	= query("name");
$HADP_br	= query($brrp."/name");
$HAPD_eapuserfile = $EAPUSERFILE;
$HAPD_topologyfile = $TOPOLOGYFILE;
$HAPD_wlinf1 = $WLINF1;
$HAPD_wlinf2 = $WLINF2;
$HAPD_wlinf3 = $WLINF3;
$HAPD_wlinf4 = $WLINF4;

anchor($wifi);
$authtype	= query("authtype");
$encrtype	= query("encrtype");
$Rport		= query("nwkey/eap/port");
$Rhost		= query("nwkey/eap/radius");
$Rsecret	= query("nwkey/eap/secret");
$wpapsk		= query("nwkey/psk/key");
//$rkeyint	= query("wpa/grp_rekey_interval");
$ssid		= query("ssid");
$HAPD_wps	= query("wps/enable");

sync_wps_configured();
//brand

$HAPD_wps_configured = query("wps/configured");
if("" == $HAPD_wps_configured) $HAPD_wps_configured = 0;

$HAPD_wps_pin = query("wps/pin");
if ("" == $HAPD_wps_pin) 
{
	$HAPD_wps_pin = query("/runtime/devdata/pin");
	if ("" == $HAPD_wps_pin) 
	{
		$HAPD_wps_pin = "12345670";
	}
}
$vendor     = query("/runtime/device/vendor");
$model      = query("/runtime/device/modelname");
$producturl = query("/runtime/device/producturl");
setattr("mac",  "get", "devdata get -e wanmac");
setattr("guid", "get", "genuuid -s \"".$dtype."\" -m \"".query("mac")."\"");
$UUID       = query("guid");
$USE_UPNP   = query($br."/upnp/count");
$HAPD_wep=0;
if		($authtype=="OPEN")		{ $HAPD_wpa=0; $HAPD_ieee8021x=0; }	/* Open					*/
else if	($authtype=="SHARED")	{ $HAPD_wpa=0; $HAPD_ieee8021x=0; }	/* Shared-Key			*/
else if	($authtype=="WPA")		{ $HAPD_wpa=1; $HAPD_ieee8021x=1; }	/* WPA					*/
else if	($authtype=="WPAPSK")	{ $HAPD_wpa=1; $HAPD_ieee8021x=0; }	/* WPA-PSK				*/
else if	($authtype=="WPA2")		{ $HAPD_wpa=2; $HAPD_ieee8021x=1; }	/* WPA2					*/
else if	($authtype=="WPA2PSK")	{ $HAPD_wpa=2; $HAPD_ieee8021x=0; }	/* WPA2-PSK				*/
else if	($authtype=="WPA+2")	{ $HAPD_wpa=3; $HAPD_ieee8021x=1; }	/* WPA+WPA2				*/
else if	($authtype=="WPA+2PSK")	{ $HAPD_wpa=3; $HAPD_ieee8021x=0; }	/* WPA-PSK + WPA2-PSK	*/

/* Create config file for hostapd */
/* new version of hostapd will not use "driver" */
//echo "driver=broadcom\n";
//echo "driver=ralink\n";
echo "eapol_key_index_workaround=0\n";
echo "logger_syslog=0\nlogger_syslog_level=0\nlogger_stdout=0\nlogger_stdout_level=0\ndebug=0\n";

echo "interface="	.$HAPD_inf		."\n";
echo "bridge="		.$HADP_br		."\n";
echo "ssid="		.$ssid			."\n";

	/* Generate WPA config */
echo "wpa="			.$HAPD_wpa		."\n";
echo "ieee8021x="	.$HAPD_ieee8021x."\n";

	/* Generate WPS config */
	//where to put UUID
	//TODO 
	echo "wps_uuid=".$UUID."\n";

	//WPS security file
	echo "dump_file=/tmp/hostapd.dump\n";
	echo "ctrl_interface=/var/run/hostapd\n";
	echo "ctrl_interface_group=0\n";
	echo "auth_algs=1\n";
	echo "wps_auth_type_flags=0x003f\n";	//fixed
	echo "wps_encr_type_flags=0x000f\n";	//fixed
	if ($HAPD_ieee8021x	==0)
	{
	echo "eap_server=1\n";								//??
	}
	else
	{
 		echo "eap_server=0\n";
	}
	
	if ($HAPD_wps==1&& $HAPD_ieee8021x ==0) { echo "wps_disable=0\n"; } else { echo "wps_disable=1\n"; }
	if ($USE_UPNP==1) { echo "wps_upnp_disable=0\n"; } else { echo "wps_upnp_disable=1\n"; }
	echo "wps_version=0x10\n";						//fixed
	//TODO
	echo "wps_default_pin=".$HAPD_wps_pin."\n";	
	echo "wps_configured=".$HAPD_wps_configured."\n";
	echo "wps_conn_type_flags=0x01\n";		
	echo "wps_config_methods=0x0086\n";
	echo "wps_rf_bands=0x03\n";
	echo "wps_manufacturer=".$vendor."\n";
	echo "wps_manufacturer_url=".$producturl."\n";
	echo "wps_serial_number=00000000\n";
	echo "wps_model_number=00000000\n";
	echo "wps_model_name=".$model."\n";
	echo "wps_model_description=".$vendor." ".$model." Wireless Broadband Router\n";
	echo "wps_friendly_name=".$model."\n";
	echo "wps_dev_name=".$model."\n";
	echo "wps_dev_category=6\n";
	echo "wps_dev_sub_category=1\n";
	echo "wps_dev_oui=0050f204\n";
	echo "wps_os_version=0x00000001\n";
	echo "wps_atheros_extension=0\n";
	echo "wme_enabled=1\n";
	echo "eap_user_file=".$HAPD_eapuserfile."\n";
	echo "wps_helper=/etc/scripts/wps.sh\n";

	fwrite("w", $HAPD_eapuserfile, "\"WFA-SimpleConfig-Registrar-1-0\" WPS\n");
	fwrite("a", $HAPD_eapuserfile, "\"WFA-SimpleConfig-Enrollee-1-0\" WPS\n");

if ($HAPD_wpa > 0)
{
	if		($rkeyint!="")			{ echo "wpa_group_rekey=".$rkeyint."\n";}
	if		($encrtype=="TKIP")		{ echo "wpa_pairwise=TKIP\n";		}
	else if	($encrtype=="AES")		{ echo "wpa_pairwise=CCMP\n";		}
	else if	($encrtype=="TKIP+AES")	{ echo "wpa_pairwise=TKIP CCMP\n";	}

	if ($HAPD_ieee8021x == 1)
	{
		echo "wpa_key_mgmt=WPA-EAP\n";
		echo "auth_server_addr=".$Rhost."\n";
		echo "auth_server_port=".$Rport."\n";
		echo "auth_server_shared_secret=".$Rsecret."\n";
	}
	else
	{
		echo "wpa_key_mgmt=WPA-PSK\n";
		if (query("nwkey/psk/passphrase")=="1")	{echo "wpa_passphrase=".$wpapsk."\n";}
		else									{echo "wpa_psk=".$wpapsk."\n";}
	}
}
else if	($encrtype=="WEP")	
{ 
	if($authtype=="SHARED")
	{
		echo "auth_algs=2\n";
	}
	else
	{
		echo "auth_algs=1\n";
	}
	$HAPD_wep=1; 
}
else/*open*/
{
	echo "auth_algs=1\n";
}

if ($HAPD_wep > 0)
{
	$conf_idx=query($wifi."/nwkey/wep/defkey");
	$conf_idx=$conf_idx-1;
	echo "wep_default_key=".$conf_idx."\n";
	$is_ascii=query($wifi."/nwkey/wep/ascii");
	$idx=1;
	$conf_idx=0;
	while($idx<5)
	{
		$key=query($wifi."/nwkey/wep/key:".$idx);
		if( $key!="")
		{
			if($is_ascii==1)
			{
				echo "wep_key".$conf_idx."=\"".$key."\"\n";
			}
			else
			{
				echo "wep_key".$conf_idx."=".$key."\n";
			}
		}
		$idx++;
		$conf_idx++;		
	}	
}


function is_inf_exist($wlinf)
{
	foreach("/runtime/phyinf")
	{
		$name = query("name");
		if ($name == $wlinf) 
		{
			$mac = query("/runtime/phyinf:".$InDeX."/macaddr/");
			break;
		}
	}
		
	if($mac == "")
	{
		//TRACE_error("device ".$wlinf." doesn't exist\n");
		return 0;	
	} else
	{
		//TRACE_error("device ".$wlinf." exist\n");
		return 1;	
	}
}

function create_topology_file($topology_file, $bridge_inf, $wlinf1, $wlinf2, $wlinf3, $wlinf4, $hostapdcfg1, $hostapdcfg2 ,$hostapdcfg3, $hostapdcfg4)
{
	/* we need to be really sure that the wireless interface is up and running. Just checking the node <active> 
	   from DB is not enough. It will have problem when doing schedule. 
	   We add check : using "ip" command to check if interface is really up or not. 	
	*/	
	if(is_inf_exist($wlinf1) == "1")	{ 	$wlinf1_exist = "1"; }
	if(is_inf_exist($wlinf2) == "1")	{ 	$wlinf2_exist = "1"; }
	if(is_inf_exist($wlinf3) == "1")	{ 	$wlinf3_exist = "1"; }
        if(is_inf_exist($wlinf4) == "1")	{ 	$wlinf4_exist = "1"; }
	fwrite("w", $topology_file, "# Auto generated topology file by hostapdcfg.php\n");
	fwrite("a", $topology_file, "bridge ".$bridge_inf."\n");
	fwrite("a", $topology_file, "{ \n");
	
	if($wlinf1 != "") {fwrite("a", $topology_file, "\tinterface ".$wlinf1."\n");}
	if($wlinf2 != "") {fwrite("a", $topology_file, "  interface ".$wlinf2."\n");}
	if($wlinf3 != "") {fwrite("a", $topology_file, "  interface ".$wlinf3."\n");}
	if($wlinf4 != "") {fwrite("a", $topology_file, "  interface ".$wlinf4."\n");}
	
	fwrite("a", $topology_file, "} \n");
	
	if($wlinf1 != "" && $wlinf1_exist=="1")
	{
		fwrite("a", $topology_file, "radio wifi0\n");
		fwrite("a", $topology_file, "{ \n");
		fwrite("a", $topology_file, "\tap \n");
		fwrite("a", $topology_file, "\t{\n");
		fwrite("a", $topology_file, "\t\tbss ".$wlinf1." \n");
		fwrite("a", $topology_file, "\t\t\t{\n");
		fwrite("a", $topology_file, "\t\t\t\tconfig ".$hostapdcfg1."\n");
		fwrite("a", $topology_file, "\t\t\t}\n");
		fwrite("a", $topology_file, "\t}\n");
		fwrite("a", $topology_file, "}\n");
	}

	if($wlinf2 != "" && $wlinf2_exist=="1")
	{
		fwrite("a", $topology_file, "radio wifi1\n");
		fwrite("a", $topology_file, "{ \n");
		fwrite("a", $topology_file, "\tap \n");
		fwrite("a", $topology_file, "\t{\n");
		fwrite("a", $topology_file, "\t\t bss ".$wlinf2." \n");
		fwrite("a", $topology_file, "\t\t\t{\n");
		fwrite("a", $topology_file, "\t\t\t\tconfig ".$hostapdcfg2."\n");
		fwrite("a", $topology_file, "\t\t\t}\n");
		fwrite("a", $topology_file, "\t}\n");
		fwrite("a", $topology_file, "    }	\n");
		fwrite("a", $topology_file, "\n");	
	}

	if($wlinf3 != "" && $wlinf3_exist=="1")
	{
		fwrite("a", $topology_file, "radio wifi2\n");
		fwrite("a", $topology_file, "{\n");
		fwrite("a", $topology_file, "\tap \n");
		fwrite("a", $topology_file, "\t{\n");
		fwrite("a", $topology_file, "\t\tbss ".$wlinf3." \n");
		fwrite("a", $topology_file, "\t\t\t{\n");
		fwrite("a", $topology_file, "\t\t\t\tconfig ".$hostapdcfg3."\n");
		fwrite("a", $topology_file, "\t\t\t}\n");
		fwrite("a", $topology_file, "\t}\n");
		fwrite("a", $topology_file, "  }	\n");
		fwrite("a", $topology_file, "\n");	
	}
	if($wlinf4 != "" && $wlinf4_exist=="1")
	{
		fwrite("a", $topology_file, "radio wifi3\n");
		fwrite("a", $topology_file, "{\n");
		fwrite("a", $topology_file, "\tap \n");
		fwrite("a", $topology_file, "\t{\n");
		fwrite("a", $topology_file, "\t\tbss ".$wlinf4." \n");
		fwrite("a", $topology_file, "\t\t\t{\n");
		fwrite("a", $topology_file, "\t\t\t\tconfig ".$hostapdcfg4."\n");
		fwrite("a", $topology_file, "\t\t\t}\n");
		fwrite("a", $topology_file, "\t}\n");
		fwrite("a", $topology_file, "}\n");
		fwrite("a", $topology_file, "\n");	
	}
}

if($HAPD_topologyfile != "")
{
	//Create topology file for dual band
	create_topology_file($HAPD_topologyfile, $HADP_br, $HAPD_wlinf1, $HAPD_wlinf2,$HAPD_wlinf3,$HAPD_wlinf4, $HOSTAPDCFG1, $HOSTAPDCFG2 ,$HOSTAPDCFG3, $HOSTAPDCFG4);
}

?>

