#!/bin/sh
echo [$0] ... > /dev/console
# neaps_run.php >>>
<?
$configfile="/var/run/neapc.conf";
$portnumber="55000";  
$ap_neaps_pid = "/var/run/apneaps_array.pid";
$apneaps_v2_pid = "/var/run/apneaps_v2.pid";
/* sandy 2009_10_26	 */
if ($generate_start==1 && query("/wlan/inf:1/aparray_enable")==1)
{ 
    		echo "echo enter Start Ap Array Neaps ... > /dev/console\n";
	$LANIF=query("/runtime/layout/lanif");
	echo "echo Start Ap Array apneaps Server ... > /dev/console\n";
	echo "neaps_array -i ".$LANIF." -c ".$configfile." -p ".$portnumber." &> /dev/console\n";
	echo "echo $! > ".$ap_neaps_pid."\n";
	echo "brctl block_aparray br0 1\n"; //phelpsll for admin IP 2010/4/19
}
else
{
	echo "echo Stop apneaps Server ... > /dev/console\n";
	echo "if [ -f ".$ap_neaps_pid." ]; then\n";
	echo "kill -9 \`cat ".$ap_neaps_pid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$ap_neaps_pid."\n";
	echo "fi\n\n";
	echo "brctl block_aparray br0 0\n"; //phelpsll for admin IP 2010/4/19
}
?>
# neaps_run.php <<<
