#!/bin/sh
<?
/*Check if schedule is enable*/
$schedule_enable=query("/schedule/enable");
$multi_ssid_state_g=query("/wlan/inf:1/multi/state");
$multi_ssid_state_a=query("/wlan/inf:2/multi/state");
$WLAN_g="/wlan/inf:1";  // b, g, n band
$WLAN_a="/wlan/inf:2";  // a band

$wlan_g_enable = query($WLAN_g."/enable");
//$wlan_a_enable = query($WLAN_a."/enable");
$wlan_a_enable = 0;
if ($schedule_enable==1)
{
	/*Start wlan and all wireless port ath* will enter forwarding state*/	
	    for("/schedule/rule/index")
	    {
		if(query("/schedule/rule/index:".$@."/enable")==1)
        	{
		$sun=query("/schedule/rule/index:".$@."/sun");
                $mon=query("/schedule/rule/index:".$@."/mon");
                $tue=query("/schedule/rule/index:".$@."/tue");
                $wed=query("/schedule/rule/index:".$@."/wed");
                $thu=query("/schedule/rule/index:".$@."/thu");
                $fri=query("/schedule/rule/index:".$@."/fri");
                $sat=query("/schedule/rule/index:".$@."/sat");
                $allday=query("/schedule/rule/index:".$@."/allday");
                $starttime=query("/schedule/rule/index:".$@."/starttime");
                $endtime=query("/schedule/rule/index:".$@."/endtime");
                $wirelesson=query("/schedule/rule/index:".$@."/wirelesson");
                $ssidnum=query("/schedule/rule/index:".$@."/ssidnum");
                      
	        $Servd_ssid_cmd="service SSID".$ssidnum." schedule";
                if ($wirelesson!="1") {$Servd_ssid_cmd=$Servd_ssid_cmd."!";}
                $Servd_ssid_cmd=$Servd_ssid_cmd." ";
                $dot="0";
		if ($sun=="1")
		{
			$Servd_ssid_cmd=$Servd_ssid_cmd."Sun";$dot="1";
		}
		
		if ($mon=="1")
                {
                	if ($dot==1) {$Servd_ssid_cmd=$Servd_ssid_cmd.",";}
                        $Servd_ssid_cmd=$Servd_ssid_cmd."Mon";$dot="1";
		}
                if ($tue=="1")
                {
                if ($dot==1) {$Servd_ssid_cmd=$Servd_ssid_cmd.",";}
                $Servd_ssid_cmd=$Servd_ssid_cmd."Tue";$dot="1";
                }
                if ($wed=="1")
		{
		if ($dot==1) {$Servd_ssid_cmd=$Servd_ssid_cmd.",";}
		$Servd_ssid_cmd=$Servd_ssid_cmd."Wed";$dot="1";
		}
		if ($thu=="1")
		{
		if ($dot==1) {$Servd_ssid_cmd=$Servd_ssid_cmd.",";}
		$Servd_ssid_cmd=$Servd_ssid_cmd."Thu";$dot="1";
		}
		if ($fri=="1")
		{
		if ($dot==1) {$Servd_ssid_cmd=$Servd_ssid_cmd.",";}
		$Servd_ssid_cmd=$Servd_ssid_cmd."Fri";$dot="1";
		}
		if ($sat=="1")
		{
		if ($dot==1) {$Servd_ssid_cmd=$Servd_ssid_cmd.",";}
		$Servd_ssid_cmd=$Servd_ssid_cmd."Sat";$dot="1";
		}
		if ($allday=="1")
		{
		$Servd_ssid_cmd=$Servd_ssid_cmd." 00:00 24:00";
		}
		else
		{
		$Servd_ssid_cmd=$Servd_ssid_cmd." ".$starttime." ".$endtime;
		}
		if($ssidnum<"8")
		{
			if ($wlan_g_enable == "1")
			{
				if ($multi_ssid_state_g == "1")
				{
					echo "ifconfig ath".$ssidnum." down\n";
					echo "service SSID".$ssidnum." stop\n";
					echo "service SSID".$ssidnum." delete\n";
					echo $Servd_ssid_cmd."\n";
				}
				else 
				{
					if($ssidnum == "0")
					{
						echo "ifconfig ath".$ssidnum." down\n";
						echo "service SSID".$ssidnum." stop\n";
						echo "service SSID".$ssidnum." delete\n";
						echo $Servd_ssid_cmd."\n";						
					}
				}
			}
		}
                else
		{
			if ($wlan_a_enable == "1")
			{
				if ($multi_ssid_state_a == "1")
				{
					$ssidnum_tmp="8"+$ssidnum;
					echo "ifconfig ath".$ssidnum_tmp." down\n";
					echo "service SSID".$ssidnum." stop\n";
					echo "service SSID".$ssidnum." delete\n";
					echo $Servd_ssid_cmd."\n";
                                }
                                else
                                {
					if ($ssidnum == "8")
					{
						$ssidnum_tmp="8"+$ssidnum;
						echo "ifconfig ath".$ssidnum_tmp." down\n";
						echo "service SSID".$ssidnum." stop\n";
						echo "service SSID".$ssidnum." delete\n";
						echo $Servd_ssid_cmd."\n";
					}
				}
			}
		}
	    }
	}
}
else
{
	for("/schedule/rule/index")
	{
		if(query("/schedule/rule/index:".$@."/enable")==1)
        	{
                $ssidnum=query("/schedule/rule/index:".$@."/ssidnum");
	        $Servd_ssid_cmd="service SSID".$ssidnum." stop";
		echo $Servd_ssid_cmd."\n";
			if ($ssidnum<"8")
			{
				echo "ifconfig ath".$ssidnum." up\n";	
			}
			else {
				$ssidnum_tmp="8"+$ssidnum;
				echo "ifconfig ath".$ssidnum_tmp." up\n";
			}
		}
	}
}
?>


