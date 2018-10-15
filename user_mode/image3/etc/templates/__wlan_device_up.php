#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
$wlan_g_enable = query("/wlan/inf:1/enable");
//$wlan_a_enable = query("/wlan/inf:2/enable");
$wlan_a_enable = 0;
$wlan_ap_operate_mode_g = query("/wlan/inf:1/ap_mode");
$wlan_ap_operate_mode_a = query("/wlan/inf:2/ap_mode");
$wds_a = -1;
$wds_g = -1;
$apc_a = -1;
$apc_g = -1;
$schedule_enable=query("/schedule/enable");
$multicast_limit= query("/wlan/inf:1/multicast_bwctrl");
$multicast_bandrate = query("/wlan/inf:1/multicast_bw_rate");
if ($schedule_enable==1)
{
        $schedule_enable_g=query("/wlan/inf:1/multi/index:0/schedule_state");
        $schedule_enable_a=query("/wlan/inf:2/multi/index:0/schedule_state");
}
else
{
        $schedule_enable_g=1;
        $schedule_enable_a=1;
}

require("/etc/templates/troot.php");
if($wlan_g_enable == 1)
{
	require($template_root."/__wlan_device_g_up.php");
	if($wlan_ap_operate_mode_g == 3 || $wlan_ap_operate_mode_g == 4)	{ $wds_g = 1; }
	if($wlan_ap_operate_mode_g == 1 )        { $apc_g = 1; }
}
if($wlan_a_enable == 1)
{
	require($template_root."/__wlan_device_a_up.php");
	if($wlan_ap_operate_mode_a == 3 || $wlan_ap_operate_mode_a == 4)	{ $wds_a = 1; }
	if($wlan_ap_operate_mode_a == 1 )        { $apc_a = 1; }
}
if($Brctl_igmp_g == 1 || $Brctl_igmp_a == 1)
{
	echo "brctl igmp_snooping br0 1\n";
}
else
{
	echo "brctl igmp_snooping br0 0\n";
}
if($wds_g == 1 || $wds_a == 1)  
{
	$wlanmac= query("/runtime/layout/wlanmac");
	echo "ifconfig br0 hw ether ".$wlanmac." \n";  
}
//echo "brctl stp br0 1 \n";
// brctl works on br0, which will affect both 2.4G and 5G, so if 2.4G is wds mode, while 5G not, how to set stp?
if($apc_g == 1 || $apc_a == 1)
{
	$clonetype = query("/wlan/inf:1/macclone/type");
	if ($clonetype != 0){
        	$wlanmac= query("/runtime/layout/wlanmac");
                echo "ifconfig br0 hw ether ".$wlanmac." \n";
        }

}
if($wlan_a_enable == 1 || $wlan_g_enable == 1)
{
        if($multicast_limit==1)
        {
                echo "brctl setmcbwctrl br0 ".$multicast_bandrate."\n";
        }
        else
        {
                echo "brctl setmcbwctrl br0 0\n";
        }
}
?>
