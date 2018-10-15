<?
/* vi: set sw=4 ts=4: */
$HAPD_interface	= $wlanif_g;
$HAPD_bridge	= "br0";
$HAPD_conf	= "/var/run/hostapd.".$HAPD_interface.".ap_bss";
$authtype	= query("/wlan/inf:1/authentication");
$encrtype	= query("/wlan/inf:1/wpa/wepmode");
$Rport		= query("/wlan/inf:1/wpa/radiusport");
$Rhost		= query("/wlan/inf:1/wpa/radiusserver");
$Rsecret	= query("/wlan/inf:1/wpa/radiussecret");
$BackupRport	= query("/wlan/inf:1/wpa/b_radiusport");
$BackupRhost	= query("/wlan/inf:1/wpa/b_radiusserver");
$BackupRsecret	= query("/wlan/inf:1/wpa/b_radiussecret");
//$own_ip_addr	= query("/wan/rg/inf:1/static/ip");
$ip_mode = query("/wan/rg/inf:1/mode"); /* 1:static, 2:dynamic */
$own_dynamic_ip_addr    = query("/runtime/wan/inf:1/ip");
$own_static_ip_addr     = query("/wan/rg/inf:1/static/ip");
if($ip_mode==2 && $own_dynamic_ip_addr!="")
{
        $own_ip_addr    = $own_dynamic_ip_addr;
} else {
        $own_ip_addr    = $own_static_ip_addr;
}
$nas_identifier	= query("/runtime/layout/lanmac");
$VLAN_state = query("/sys/vlan_state");
$NAP_enable	= query("/sys/vlan_mode");
$Aenable	= query("/wlan/inf:1/wpa/acctstate");
$Aport		= query("/wlan/inf:1/wpa/acctport");
$Ahost		= query("/wlan/inf:1/wpa/acctserver");
$Asecret	= query("/wlan/inf:1/wpa/acctsecret");
$BackupAport	= query("/wlan/inf:1/wpa/b_acctport");
$BackupAhost	= query("/wlan/inf:1/wpa/b_acctserver");
$BackupAsecret	= query("/wlan/inf:1/wpa/b_acctsecret");

if(query("/wlan/inf:1/autorekey/ssid/enable")==1 && query("/runtime/wlan/inf:1/wpa/wpapsk")!="")//add for autoredey by yuda
{
	$wpapsk     = query("/runtime/wlan/inf:1/wpa/wpapsk");

}
else if(query("/wlan/inf:1/wpa/wpapsk")!="")
{
$wpapsk		= query("/wlan/inf:1/wpa/wpapsk");
}
else
{
	$wpapsk = 00000000;
}

$wpapskformat	= query("/wlan/inf:1/wpa/passphraseformat");
$rkeyint	= query("/wlan/inf:1/wpa/grp_rekey_interval");
$ssid		= query("/wlan/inf:1/ssid");
$DyWepKeyLen	= query("/wlan/inf:1/d_wepkeylen");
$DyWepRKeyInt	= query("/wlan/inf:1/d_wep_rekey_interval");

$EmRadiusState	=	query("/wlan/inf:1/wpa/embradius/state");
$EmRadiusCertState	=	query("/wlan/inf:1/wpa/embradius/certstate");
$EmRadiusEAPUser_conf	=	"/var/hostapd_g.eap_user";
$EmRadiusDefaultCA	= "/etc/templates/certs/cacert.pem";
$EmRadiusDefaultCAKey	=	"/etc/templates/certs/cakey.key";
$EmRadiusDefaultCAPass	=	"DEFAULT";
$EmRadiusEapUser	=	"/wlan/inf:1/wpa/embradius/eap_user/index";
$EmRadiusE_SrvCert	=	"/var/etc/certs/hostapd/eca_srvcert.pem";
$EmRadiusE_SrvKey	=	"/var/etc/certs/hostapd/eca_srvkey.key";
$EmRadiusE_Srv_KeyPass	=	query("/wlan/inf:1/wpa/embradius/eca_keypasswd");

if	($authtype==0) { $HAPD_wpa=0; $HAPD_ieee8021x=0; }	/* Open					*/
else if	($authtype==1) { $HAPD_wpa=0; $HAPD_ieee8021x=0; }	/* Shared-Key			*/
else if	($authtype==2) { $HAPD_wpa=1; $HAPD_ieee8021x=1; }	/* WPA					*/
else if	($authtype==3) { $HAPD_wpa=1; $HAPD_ieee8021x=0; }	/* WPA-PSK				*/
else if	($authtype==4) { $HAPD_wpa=2; $HAPD_ieee8021x=1; }	/* WPA2					*/
else if	($authtype==5) { $HAPD_wpa=2; $HAPD_ieee8021x=0; }	/* WPA2-PSK				*/
else if	($authtype==6) { $HAPD_wpa=3; $HAPD_ieee8021x=1; }	/* WPA+WPA2				*/
else if	($authtype==7) { $HAPD_wpa=3; $HAPD_ieee8021x=0; }	/* WPA-PSK + WPA2-PSK	*/
else if ($authtype==9) { $HAPD_wpa=0; $HAPD_ieee8021x=1; }	/* 802.1x Only          */

if(query("/wlan/inf:1/autorekey/ssid/enable")==1 && query("/runtime/wlan/inf:1/wpa/wpapsk")!="")//add for autorekey by log luo
{
        $wpapsk     = query("/runtime/wlan/inf:1/wpa/wpapsk");

}
else if(query("/wlan/inf:1/wpa/wpapsk")!="")
{
$wpapsk         = query("/wlan/inf:1/wpa/wpapsk");
}
else
{
        $wpapsk = 00000000;
}

/* Create config file for hostapd */
fwrite($HAPD_conf,
	"bridge=br0\n".
	"interface=".$HAPD_interface."\n".
	"logger_syslog=0\n".
	"logger_syslog_level=0\n".
	"logger_stdout=0\n".
	"logger_stdout_level=0\n".
	"eapol_key_index_workaround=0\n".
	"dtim_period=2\n".
	"max_num_sta=255\n".
	"macaddr_acl=0\n".
	"auth_algs=1\n".
	"ignore_broadcast_ssid=0\n".
	"wme_enabled=0\n".
	"eapol_version=2\n".
	"ssid=".$ssid."\n".
	"wpa=".$HAPD_wpa."\n".
	"ieee8021x=".$HAPD_ieee8021x."\n");

if ($HAPD_wpa > 0)
{
	if ($rkeyint!="")	{ fwrite2($HAPD_conf, "wpa_group_rekey=".$rkeyint."\n");}
	if	($encrtype==2)	{ fwrite2($HAPD_conf, "wpa_pairwise=TKIP\n");		}
	else if	($encrtype==3)	{ fwrite2($HAPD_conf, "wpa_pairwise=CCMP\n");		}
	else if	($encrtype==4)	{ fwrite2($HAPD_conf, "wpa_pairwise=TKIP CCMP\n");	}

	if ($HAPD_ieee8021x == 1)
	{
		fwrite2($HAPD_conf, "wpa_key_mgmt=WPA-EAP\n");
	}
	else
	{
		fwrite2($HAPD_conf, "wpa_key_mgmt=WPA-PSK\n");
		if(query("/wlan/inf:1/autorekey/ssid/enable")==1)   {$wpapskformat = 1;}
		if ($wpapskformat == 1)	{fwrite2($HAPD_conf, "wpa_passphrase=".$wpapsk."\n");}
		else			{fwrite2($HAPD_conf, "wpa_psk=".$wpapsk."\n");}
	}
}

if ($HAPD_ieee8021x == 1)
{

	if($EmRadiusState == 1){	/* RadiusOnBoard, 0:Disable, 1:Enable */
		if($EmRadiusCertState == 1){	/* 0:Use Default Root CA Cert, 1:Use External Server Cert */
			fwrite2($HAPD_conf,	"server_cert=".$EmRadiusE_SrvCert."\n");
			fwrite2($HAPD_conf,	"private_key=".$EmRadiusE_SrvKey."\n");
			fwrite2($HAPD_conf,	"private_key_passwd=".$EmRadiusE_Srv_KeyPass."\n");
		}
		else	/* 0: Use Default Root CA */
		{
			fwrite2($HAPD_conf,	"server_cert=".$EmRadiusDefaultCA."\n");
			fwrite2($HAPD_conf,	"private_key=".$EmRadiusDefaultCAKey."\n");
			fwrite2($HAPD_conf,	"private_key_passwd=".$EmRadiusDefaultCAPass."\n");
		}
		fwrite2($HAPD_conf, "eap_server=1\n");
	//	fwrite($EmRadiusEAPUser_conf, "# Phase 1 users\n");
	//	fwrite2($EmRadiusEAPUser_conf, "*\tPEAP\n");
	//	fwrite2($EmRadiusEAPUser_conf, "# Phase 2 (tunnelled within EAP-PEAP or EAP-TTLS) users\n");
	//	for($EmRadiusEapUser)
	//	{
	//		$EmRadiusEapUserName = query($EmRadiusEapUser.":".$@."/name");
	//		if($EmRadiusEapUserName != "")
	//		{
	//			$EmRadiusEapUserPasswd = query($EmRadiusEapUser.":".$@."/passwd");
	//			fwrite2($EmRadiusEAPUser_conf, "\"".$EmRadiusEapUserName."\"\tMSCHAPV2\t\"".$EmRadiusEapUserPasswd."\"\t[2]\n");
	//		}
	//	}
		fwrite2($HAPD_conf, "eap_user_file=".$EmRadiusEAPUser_conf."\n");
	}
	else
	{
		/* Radius Server Seetings */
		fwrite2($HAPD_conf, "auth_server_addr=".$Rhost."\n");
		fwrite2($HAPD_conf, "auth_server_port=".$Rport."\n");
		fwrite2($HAPD_conf, "auth_server_shared_secret=".$Rsecret."\n");
		
		if($BackupRhost != "" && $BackupRport != "" && $BackupRsecret != "")
		{
			fwrite2($HAPD_conf, "auth_server_addr=".$BackupRhost."\n");
			fwrite2($HAPD_conf, "auth_server_port=".$BackupRport."\n");
			fwrite2($HAPD_conf, "auth_server_shared_secret=".$BackupRsecret."\n");
		}
		fwrite2($HAPD_conf, "own_ip_addr=".$own_ip_addr."\n");
		fwrite2($HAPD_conf, "nas_identifier=".$nas_identifier."\n");

		/* Disable EAP reauthentication */
	//	fwrite2($HAPD_conf, "eap_reauth_period=0\n");

		/* Radius Retry Primary Interval */
		fwrite2($HAPD_conf, "radius_retry_primary_interval=600\n");

		/* Accounting Server Settings */
		if ($Aenable == 1)
		{
			fwrite2($HAPD_conf, "acct_server_addr=".$Ahost."\n");
			fwrite2($HAPD_conf, "acct_server_port=".$Aport."\n");
			fwrite2($HAPD_conf, "acct_server_shared_secret=".$Asecret."\n");
			if($BackupAhost != "" && $BackupAport != "" && $BackupAsecret != "")
			{
				fwrite2($HAPD_conf, "acct_server_addr=".$BackupAhost."\n");
				fwrite2($HAPD_conf, "acct_server_port=".$BackupAport."\n");
				fwrite2($HAPD_conf, "acct_server_shared_secret=".$BackupAsecret."\n");
			}
		}
	}

	/* 802.1x support for dynamic WEP keying */
	if ($HAPD_wpa == 0)
	{
		/* Key lengths , 5 = 64-bit , 13 = 128-bit (default: 128-bit) */
		if ($DyWepKeyLen == 64)
		{
			fwrite2($HAPD_conf, "wep_key_len_unicast=5\n");
			fwrite2($HAPD_conf, "wep_key_len_broadcast=5\n");
		}
		else/* DyWepKeyLen == 128 */
		{
			fwrite2($HAPD_conf, "wep_key_len_unicast=13\n");
			fwrite2($HAPD_conf, "wep_key_len_broadcast=13\n");
		}

		/* Rekeying period in seconds (default:300 secs) */
		if ($DyWepRKeyInt != "")	{	fwrite2($HAPD_conf, "wep_rekey_period=".$DyWepRKeyInt."\n");	}
	}

	fwrite2($HAPD_conf, "eap_reauth_period=0\n");

	/* NAP Support */
	if ($VLAN_state == 1 && $NAP_enable!="")	{ fwrite2($HAPD_conf, "nap_enable=".$NAP_enable."\n");}	

	/* Enable PreAuthentication */
	fwrite2($HAPD_conf, "rsn_preauth=1\n");
	fwrite2($HAPD_conf, "rsn_preauth_interfaces=br0\n");

}

?>
