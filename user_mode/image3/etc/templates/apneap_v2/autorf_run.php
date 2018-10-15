#!/bin/sh               
<?
$SWC = query("/sys/swcontroller/enable");
$APARRAY = query("/wlan/inf:1/aparray_enable");
$AUTORF = query("/sys/autorf/enable");
if ($generate_start==1 && $AUTORF==1)
{
	echo "echo enter Start autoRF client ... > /dev/console\n";
	if($SWC==1) {
		echo "autorfc -m 2 -t 3 &> /dev/console";
	} else if($APARRAY==1 /*&& query("/wlan/inf:1/arrayrole_original")==3*/) {
		echo "autorfc -m 1 -t 3 &> /dev/console";
	}
	else {
		echo "echo not SWC client, and not AP array salve ... > /dev/console";
	}
}
else
{
	echo "echo Stop autorf client ... > /dev/console\n";
	echo "killall autorfc > /dev/null 2>&1";
}
?> 
