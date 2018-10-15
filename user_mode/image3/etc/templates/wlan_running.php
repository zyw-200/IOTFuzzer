#!/bin/sh
<? /* vi: set sw=4 ts=4: */
/*require("/etc/templates/troot.php");*/
$wlanif = query("/runtime/layout/wlanif");
$wlan_ap_operate_mode = query("/wireless/ap_mode");

if ($generate_start==1)
{
	echo "echo Start WLAN interface ".$wlanif." .running.. > /dev/console\n";
	if (query("/wireless/enable")!=1)
	   {
	        echo "echo WLAN is disabled ! > /dev/console\n";
	        exit;
	   }					
	$IWPRIV="iwpriv ".$wlanif;
    $IWCONF="iwconfig ".$wlanif;
	/* get configuration */
	anchor("/wireless");
    $w80211d    = query("w80211d");
    $autochannel	= query("autochannel");			
	if ($autochannel == 1){ 
			$dychannel = query("/runtime/stats/wireless/channel");	
	 		if ($dychannel<=4)        { echo $IWPRIV." extoffset 1\n";  }
	       	else                    { echo $IWPRIV." extoffset -1\n"; }				  
	}
	if ($wlan_ap_operate_mode==0)
	{
	if ($w80211d!="")		{ echo $IWPRIV." countryie ".$w80211d."\n"; }
	   echo "iwpriv ".$wlanif." reset 1\n";
	}
    echo "echo 0 > /proc/sys/dev/wifi0/softled\n";
}
else
{
}
?>
