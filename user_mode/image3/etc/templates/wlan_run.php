#!/bin/sh
echo [$0] ... > /dev/console
<?
$WLAN_g="/wlan/inf:1";  // b, g, n band
$WLAN_a="/wlan/inf:2";  // a band

$wlan_g_enable = query($WLAN_g."/enable");
$wlan_a_enable = 0;
$wlan_ap_operate_mode_g = query($WLAN_g."/ap_mode");
$wlan_ap_operate_mode_a = query($WLAN_a."/ap_mode");
$ethlink_a = query($WLAN_a."/ethlink");
$ethlink_g = query($WLAN_g."/ethlink");

require("/etc/templates/troot.php");
$wlanmac = query("/runtime/layout/wlanmac");
$wlanmac_a = query("/runtime/layout/wlanmac_a");

$countrycode= query("/runtime/nvram/countrycode");

$apc_g = -1;
$apc_a = -1;

if ($generate_start==1)
{
	if ($wlan_g_enable != 1 && $wlan_a_enable !=1)
	{
		echo "echo WLAN is disabled ! > /dev/console\n";
		exit;
	}

	if($wlan_g_enable == 1)
	{
	
		if($countrycode!="")
		{
			echo "iwpriv wifi0 setCountryID ".$countrycode."\n"; 
		}
		else
		{
			echo "iwpriv wifi0 setCountryID 840";
		}
		require($template_root."/wlan_run_g.php");
		if($wlan_ap_operate_mode_g == 1)	{$apc_g = 1;}
		else {$apc_g = 0;}

		echo "rgdb -i -s /runtime/stats/wireless/led11g 1\n";
	}
	if($wlan_a_enable == 1)
	{
	      $is_outdoor = query("/wlan/application");
	      if ($countrycode!=""){
		if ($countrycode==392 && $is_outdoor==1 ){	
			$countrycode=4080;
		}
	      }	
		else {
			$countrycode=840;
		}  /*for JP //OUTDOOR_APPS_JP --- */

		if($countrycode!="")
		{
			echo "iwpriv wifi1 setCountryID ".$countrycode."\n"; 
		}
		else
		{
			echo "iwpriv wifi1 setCountryID 840";
		}
		require($template_root."/wlan_run_a.php");
		if($wlan_ap_operate_mode_a == 1)	{$apc_a = 1;}
		else {$apc_a = 0;}

		echo "rgdb -i -s /runtime/stats/wireless/led11a 1\n";
	}
//common for 2.4G and 5G
	 /* ethernet integration dennis2008-02-05 start */
	if ($ethlink_a == 1 || $ethlink_g == 1){
		echo "brctl ethlink br0 1 \n";
	}
	else{
		echo "brctl ethlink br0 0\n";
	}
	/* ethernet integration dennis2008-02-05 end */

}//if end
else{
	echo "echo Stop WLAN interface ... > /dev/console\n";
	echo "rgdb -i -s /runtime/stats/wireless/led11g 0\n";
//	echo "rgdb -i -s /runtime/stats/wireless/led11a 0\n";
	
	if($wlan_g_enable == 1)
	{
		require($template_root."/wlan_run_g.php");
		if($wlan_ap_operate_mode_g == 1)	{$apc_g = 1;}
		else {$apc_g = 0;}
	}
	if($wlan_a_enable == 1)
	{
		require($template_root."/wlan_run_a.php");
		if($wlan_ap_operate_mode_a == 1)	{$apc_a = 1;}
		else {$apc_a = 0;}
	}
        echo "VAPLIST=`iwconfig | grep ath | cut -b 1-5`\n";
        echo "for i in $VAPLIST\n";
        echo "do\n";
        echo "echo killing $i\n";
        echo "wlanconfig $i destroy\n";
        echo "done\n";

	echo "sleep 1\n";
	echo "rmmod ath_pktlog"." > /dev/null 2>&1\n";
	echo "sleep 2\n";
	echo "rmmod umac"." > /dev/null 2>&1\n";
	echo "sleep 2\n";
	echo "rmmod ath_dev"." > /dev/null 2>&1\n";
	echo "rmmod ath_dfs"." > /dev/null 2>&1\n";
	echo "rmmod ath_rate_atheros"." > /dev/null 2>&1\n";
	echo "rmmod ath_hal"." > /dev/null 2>&1\n"."\n";
	echo "rmmod asf"." > /dev/null 2>&1\n";
	echo "rmmod adf"." > /dev/null 2>&1\n";
	
}


?>
