#!/bin/sh
echo [$0] ... > /dev/console
# loadbalance_run.php >>>
<?
$loadbalance_pid = "/var/run/loadbalance.pid";
$time = 3;
$LOADBALANCE = query("/sys/loadbalance/enable");
$APARRAY = query("/wlan/inf:1/aparray_enable");
$SWC = query("/sys/swcontroller/enable");
if ($generate_start==1)
{
	if($LOADBALANCE ==1)
	{
		if($APARRAY == 1)
		{
			echo "loadbalance -m 1 -t ".$time." & >  /dev/console\n";
			echo "echo $! > ".$loadbalance_pid."\n";
		}else if($SWC == 1)
		{	
			echo "loadbalance -m 2 -t ".$time." & >  /dev/console\n";
			echo "echo $! > ".$loadbalance_pid."\n";
		}
	}
}
else
{
	echo "echo Stop loadbalance ... > /dev/console\n";
	echo "VAPLIST=`iwconfig | grep ath | cut -b 1-5`\n";
        echo "for i in $VAPLIST\n";
        echo "do\n";
	echo "iwpriv $i maccmdload 3\n";   // flush the ACL database.
        echo "iwpriv $i maccmdload 0\n"; //allow
        echo "iwpriv $i loadenable 0\n"; //disabel the loadbalance in the driver
        echo "done\n";
        echo "if [ -f ".$loadbalance_pid." ]; then\n";
        echo "kill -9 \`cat ".$loadbalance_pid."\` > /dev/null \n";
        echo "rm -f ".$loadbalance_pid."\n";
        echo "fi\n\n";
}
?>
# loadbalance_run.php <<<
