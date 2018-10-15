#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$ARPTABLES="arptables";
$enable = query("/arpspoofing/enable");

/* main */
if ($generate_start==1)
{
	echo "echo Start arp spoofing prevention ... \n";

	if($enable == "" || $enable != 1){ 
		echo "echo arp spoofing prevention is disabled. \n";
		exit;
	}

	$wlanapmode_a = query("/wlan/inf:2/ap_mode");
	$wlanapmode_g = query("/wlan/inf:1/ap_mode");
	if($wlanapmode_a == 1 || $wlanapmode_g == 1){
		echo "echo arp spoofing prevention is disabled in APC mode. \n";
		exit;
	}

	$static_client = "/arpspoofing/static";

	for($static_client."/index")
	{
		$index++;
		if($index <= 8)
		{

		$ip=query($static_client."/index:".$index."/ip");
		$mac=query($static_client."/index:".$index."/mac");
		
		if($ip == "" || $mac == ""){exit;} /* error */

		echo $ARPTABLES." -A FORWARD --source-ip ".$ip." --source-mac ! ".$mac." -j DROP "." \n";

		}
	}
}
else
{
	echo "echo Stop arp spoofing prevention... \n";
	echo $ARPTABLES." -F FORWARD "." \n";
}

?>
