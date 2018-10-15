<?php
@include('sessionCheck.inc');
$opm=$_REQUEST["opmode"];
if($_REQUEST['macAddress'])
{
	$MACADDRESS = array_filter(explode(",",$_REQUEST['macAddress']));
		$x=count($MACADDRESS);
		for($i=1;$i<=$x;$i++){	
			$mac = str_replace("\'","",$MACADDRESS[$i]);
			$mac1 = str_replace("-",":", $mac);
			if($opm == 'wlan0')			
			exec(" /usr/local/sbin/wlanconfig wifi0vap0 rougeap_deauth_mac ".$mac1);
			else
			exec(" /usr/local/sbin/wlanconfig wifi1vap0 rogueap_deauth_mac ".$mac1);
		}					
	}
	unset($MACADDRESS);
	unset($str);
?>