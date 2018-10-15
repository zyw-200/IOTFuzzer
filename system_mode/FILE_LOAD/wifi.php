<?
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/xnode.php";

//$uid argument should be A band uid

function wifi_error($errno)
{
	fwrite("a", $_GLOBALS["START"], "exit ".$errno."\n");
	fwrite("a", $_GLOBALS["STOP"],  "exit ".$errno."\n");
}

function get_path_by_phy_uid($is_runtime, $uid)
{
	if ($is_runtime == 1) $base = "/runtime";
	$p = XNODE_getpathbytarget($base, "phyinf", "uid", $uid, 0);
	if ($p == "")
	{
		wifi_error("9");
		return "";
	}
	return $p;
}

function wifi_service($wifi_uid)
{
	$stsp		= XNODE_getpathbytarget("/runtime", "phyinf", "uid", $wifi_uid, 0);
	$brinf		= query($stsp."/brinf");
	$winfname	= query($stsp."/name");

	$brphyinf	= PHYINF_getphyinf($brinf);
	$phy 		= XNODE_getpathbytarget("", "phyinf", "uid", $wifi_uid, 0);
	$wifi_path  = XNODE_getpathbytarget("/wifi", "entry", "uid", query($phy."/wifi"), 0);
	$phy_rpath  = $stsp;
	
	//$upwifistatshlper		= "/etc/scripts/upwifistatshlper.sh";
	$upwifistats_pidfile	= "/var/run/updatewifistats-".$winfname.".pid";
	$upwifistatshlper_Gband	= "/etc/scripts/upwifistatshlper_G_band.sh";
	$upwifistatshlper_Aband	= "/etc/scripts/upwifistatshlper_A_band.sh";
	
	$freq 		= query($phy."/media/freq");
	
	/* stop updatewifistats & hostapd */
	//fwrite("a", $_GLOBALS["STOP"], 'service HOSTAPD stop\n');
	fwrite("a",$_GLOBALS["STOP"], "if [ -f ".$upwifistats_pidfile." ]; then\n");
	fwrite("a",$_GLOBALS["STOP"], "	PID=`cat ".$upwifistats_pidfile."`\n");
	fwrite("a",$_GLOBALS["STOP"], "	if [ \"$PID\" != \"0\" ]; then\n");
	fwrite("a",$_GLOBALS["STOP"], "		kill $PID\n");
	fwrite("a",$_GLOBALS["STOP"], "		rm -f ".$upwifistats_pidfile."\n");
	fwrite("a",$_GLOBALS["STOP"], "	fi\n");
	fwrite("a",$_GLOBALS["STOP"], "fi\n");
	
	/* for RT3090 11g */
	if ($wifi_uid == "WLAN-1")
	{
		/* config file of ralink driver. */
		fwrite("a", $_GLOBALS["START"],
			"xmldbc -P /etc/services/WIFI/rtcfg.php -V PHY_UID=".$wifi_uid." > /var/run/RT3090.dat\n");

		/* reinstall wireless driver */
		fwrite("a", $_GLOBALS["START"],
			'insmod /lib/modules/RT3090_ap_util.ko\n'.
			'insmod /lib/modules/RT3090_ap.ko\n'.
			'insmod /lib/modules/RT3090_ap_net.ko\n'
			);
		fwrite("a", $_GLOBALS["STOP"], 'brctl delif '.$brphyinf.' '.$winfname.'\n');	
		fwrite("a", $_GLOBALS["STOP"],
			'ifconfig '.$winfname.' down\n'.		
			'rmmod RT3090_ap_net\n'.
			'rmmod RT3090_ap\n'.
			'rmmod RT3090_ap_util\n'
			);
	}
	/* for RT3883 11a */	
	else if ($wifi_uid == "WLAN-2")
	{
		/* config file of ralink driver. */
		fwrite("a", $_GLOBALS["START"], "xmldbc -P /etc/services/WIFI/rtcfg.php -V PHY_UID=".$wifi_uid." > /var/run/RT2860.dat\n");

		/* reinstall wireless driver */
		fwrite("a", $_GLOBALS["START"],
			'insmod /lib/modules/rt2860v2_ap.ko\n'
			);
		
		fwrite("a", $_GLOBALS["STOP"], 'brctl delif '.$brphyinf.' '.$winfname.'\n');				
		fwrite("a", $_GLOBALS["STOP"],
			'ifconfig '.$winfname.' down\n'.
			'sleep 1\n'.
			'rmmod rt2860v2_ap\n'
			);
	}

	/* start updatewifistats */
	if($freq == "5") {	$upwifistatshlper = $upwifistatshlper_Aband; } else  {	$upwifistatshlper = $upwifistatshlper_Gband; }
	if (isfile($upwifistatshlper)==1) $upwifistatshlper=" -s ".$upwifistatshlper;
	#fwrite("a",$_GLOBALS["START"],'updatewifistats -i '.$winfname.' -x '.$phy.' -r '.$phy_rpath.' -m RT2800'.$upwifistatshlper.' &\n');
	#fwrite("a",$_GLOBALS["START"],'echo $! > '.$upwifistats_pidfile.'\n');
	
	if (query($wifi_path."/wps/enable")==1) 
	{
		/* setup the event for wps PIN & PBC */
		fwrite("a", $_GLOBALS["START"],
			'event WPSPIN add "/etc/scripts/wps.sh pin '.$wifi_uid.'"\n'.
			'event WPSPBC.PUSH add "/etc/scripts/wps.sh pbc '.$wifi_uid.'"\n');
		fwrite("a", $_GLOBALS["STOP"],
			'event WPSPIN add true\n'.
			'event WPSPBC.PUSH add true\n');
	} else
	{
		/* if disabled, we need to dismiss the related events */
		fwrite("a", $_GLOBALS["START"],
			'event WPSPIN add true\n'.
			'event WPSPBC.PUSH add true\n');
	}
	//fwrite("a", $_GLOBALS["START"],'event WPS.NONE \n');

	/* bring up the interface and bridge */
/*	fwrite("a", $_GLOBALS["START"],
		'ifconfig '.$winfname.' txqueuelen 250\n'.
		'ifconfig '.$winfname.' up\n'
		);
*/		
	/* record a global variable to let hostapd know which interface is up */	
	fwrite("a", $_GLOBALS["START"],
		'xmldbc -P /etc/services/WIFI/hostapdcfg.php -V ACTION=ADD_RECORD_INF -V WLINF='.$winfname.' > /dev/null\n'
		);
		
	fwrite("a", $_GLOBALS["STOP"],
		'xmldbc -P /etc/services/WIFI/hostapdcfg.php -V ACTION=DEL_RECORD_INF -V WLINF='.$winfname.' > /dev/null\n'
		);
	
	fwrite("a", $_GLOBALS["STOP"],'xmldbc -t \"close_WPS_led:3:event WPS.NONE\"\n');
	
/*	if ($brphyinf!="" && $winfname!="")
	{
		fwrite(a, $_GLOBALS["START"],'brctl addif '.$brphyinf.' '.$winfname.'\n');
	}

	fwrite("a", $_GLOBALS["START"],
		'brctl setbwctrl br0 '.$winfname.' 900\n'
		);
		*/
}

?>
