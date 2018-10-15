#!/bin/sh
echo [$0] ... > /dev/console
<?
    require("/etc/templates/troot.php");
//	$wlanmac    = query("/runtime/macclone/addr");
//  $wlmode     = query("/wlan/ch_mode");
//	$wlanif		= query("/runtime/layout/wlanif");
//    $wlan_ap_operate_mode = query("/wlan/inf:1/ap_mode");
//    $countrycode= query("/runtime/nvram/countrycode");
//    $wlxmlpatch_pid = "/var/run/wlxmlpatch.pid";
//    $ap_igmp_pid = "/var/run/ap_igmp.pid";
    
    $WLAN_g="/wlan/inf:1";  // b, g, n band
    $WLAN_a="/wlan/inf:2";  // a band
    $wlan_ap_operate_mode_g = query($WLAN_g."/ap_mode");
    $wlan_ap_operate_mode_a = query($WLAN_a."/ap_mode");    
  
    if ($wlan_ap_operate_mode_g==1) //g mode
    {
	$wlanif_g = query("/runtime/layout/wlanif_g");//ath0	
	require("/etc/templates/__wlan_apcmode_g.php");
        //exit;
    }
    else if ($wlan_ap_operate_mode_a==1) //a mode
    {
	$wlanif_a = query("/runtime/layout/wlanif_a");//ath16
	require("/etc/templates/__wlan_apcmode_a.php");
        //exit;
    }
?>
