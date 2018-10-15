#!/bin/sh
echo [$0] ... > /dev/console
# neaps_run.php >>>
<?
/* vi: set sw=4 ts=4: */
$configfile="/var/run/neaps.conf";
$neaps_pid = "/var/run/neaps.pid"; //2009_10_27 sandy
/* neap_2007_03_01 , Jordan */
if ($generate_start==1 && query("/sys/neap/enable")==1)
/*if ($generate_start==1 && query("/runtime/func/neaps")==1)*/
{
	$LANIF=query("/runtime/layout/lanif");
	echo "echo Start Neap Server ... > /dev/console\n";
	echo "neaps -i ".$LANIF." -c ".$configfile."  &> /dev/console\n";
	echo "echo $! > ".$neaps_pid."\n"; //2009_10_27 sandy
	echo "brctl block_neap br0 1\n"; //phelpsll for admin IP 2010/4/19
}
else
{
	echo "echo Stop Neap Server ... > /dev/console\n";
	/*2009_10_27 sandy start */
	echo "if [ -f ".$neaps_pid." ]; then\n";
	echo "kill \`cat ".$neaps_pid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$neaps_pid."\n";
	echo "fi\n\n";
		/*2009_10_27 sandy end */
	echo "brctl block_neap br0 0\n"; //phelpsll for admin IP 2010/4/19
}
?>
# neaps_run.php <<<
