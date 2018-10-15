# flush_macfilter.php >>>
<? /* vi: set sw=4 ts=4: */ ?>
iptables -t nat -F PRE_MACFILTER
<?
$enable	= query("/security/macfilter/enable");
$action	= query("/security/macfilter/action");
$log	= query("/security/log/droppacketinfo");
$logstr	= "LOG --log-level info --log-prefix DRP:004:";
$count	= 0;

$entry = 0;
for("/security/macfilter/entry") { $entry++; }

if ($entry == 0 || $enable == 0)
{	
	echo "logger -p 192.0 \"SYS:007\"\n";
}
else if ($action == 1)
{
	echo "logger -p 192.0 \"SYS:008\"\n";
	for("/security/macfilter/entry")
	{
		$mac=query("sourcemac");
		if ($mac != "")
		{
			$count++;
			echo "iptables -t nat -A PRE_MACFILTER -i ".$lanif." -m mac --mac-source ".$mac." -j RETURN\n";
		}
	}
	if ($log == 1) { echo "iptables -t nat -A PRE_MACFILTER -i ".$lanif." -j ".$logstr."\n"; }  
	echo "iptables -t nat -A PRE_MACFILTER -i ".$lanif." -j DROP\n";
}
else if ($action == 0)	
{
	echo "logger -p 192.0 \"SYS:009\"\n";
	for("/security/macfilter/entry")
	{
		$mac=query("sourcemac");
		if ($mac != "")
		{
			$count++;
			if ($log == 1) { echo "iptables -t nat -A PRE_MACFILTER -i ".$lanif." -m mac --mac-source ".$mac." -j ".$logstr."\n"; }
			echo "iptables -t nat -A PRE_MACFILTER -i ".$lanif." -m mac --mac-source ".$mac." -j DROP\n";
		}
	}
}
set("/runtime/func/macfilter", $count);
?>
# flush_macfilter.php <<< 
