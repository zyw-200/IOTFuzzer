#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$conf_file = "/var/lld2d.conf";

$lanif = query("/runtime/layout/lanif");
if (query("/wireless/enable")==1) {	$wlanif = query("/runtime/layout/wlanif"); }
else { $wlanif = ""; }

if ($generate_start==1)
{
	echo "echo Start LLD2 daemon ... > /dev/console\n";
	fwrite($conf_file, "icon = /www/pic/lld2d.ico\n");
	fwrite2($conf_file, "jumbo-icon = /www/pic/lld2d.ico\n");
	echo "lld2d -c ".$conf_file." ".$lanif." ".$wlanif." & > /dev/console\n";
}
else
{
	echo "echo Stop LLD2 daemon ... > /dev/console\n";
	$lld2d_pid = "/var/run/lld2d-br0.pid";
	echo "killall lld2d\n";
	echo "if [ -f ".$lld2d_pid." ];then\n";
	echo "rm -f ".$lld2d_pid."\n";
	echo "fi\n";
}
?>
