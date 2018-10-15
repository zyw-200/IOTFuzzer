#!/bin/sh
<?
$APARRAY = query("/wlan/inf:1/aparray_enable");
$ROLE = query("/wlan/inf:1/arrayrole_original");
if($generate_start==1 && $APARRAY==1)
{
	echo "echo Start apneaps_v2 Server ... > /dev/console\n";
	/*for roaming/autorf/loadbalance server*/
	if($ROLE == 1 || $ROLE == 2)
	{
		$LANIF=query("/runtime/layout/lanif");
		echo "echo enter Start Captive roaming/autoRF/LB apneaps ... > /dev/console\n";
		echo "apneaps -i ".$LANIF." &> /dev/console\n";
	}
}
else
{
	echo "echo Stop apneaps_v2 Server ... > /dev/console\n";
	echo "killall apneaps > /dev/null 2>&1";
}
?>
