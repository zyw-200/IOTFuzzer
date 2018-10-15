# flush_dmz.php >>>
iptables -t nat -F PRE_DMZ
<?
/* vi: set sw=4 ts=4:
 * generating rules for DMZ.
 */
$dmzcount	= 0;
$dmzenable	= query("/nat/dmzsrv/dmzaction");
if ($dmzenable==1)
{
	$dmzaddr = query("/nat/dmzsrv/ipaddress");

	if ($wanif!="" && $wanip!="" && $dmzaddr!="")
	{
		echo "iptables -t nat -A PRE_DMZ -i ".$wanif." -d ".$lanip." -j DROP\n";
		echo "iptables -t nat -A PRE_DMZ -i ".$wanif." -d ".$wanip." -j DNAT --to ".$dmzaddr."\n";
		$dmzcount+=2;
	}
	echo "logger -p 192.0 \"SYS:018[".$dmzaddr."]\"\n";
}
else
{
	echo "logger -p 192.0 \"SYS:019\"\n";
}

set("/runtime/func/dmz", $dmzcount);
?># flush_dmz.php <<<
