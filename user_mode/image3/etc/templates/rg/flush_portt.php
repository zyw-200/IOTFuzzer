# flush_portt.php >>>
<? /* vi: set sw=4 ts=4: */ ?>
[ -d /var/porttrigger ] || mkdir -p /var/porttrigger
rm -f /var/porttrigger/*
iptables -F FOR_PORTT
iptables -t nat -F PRE_PORTT
trigger -m flush
<?
$limit=" -m limit --limit 30/m --limit-burst 5";
$log  =" -j LOG --log-level info --log-prefix PTR:";

$count=0;
for("/nat/porttrigger/entry")
{
	if (query("enable")==1)
	{
		$count++;
		$prot  = query("triggerprotocol");
		$begin = query("triggerportbegin");
		$port  = query("publicport");

		if ($prot==2 || $prot==0)
		{
			echo "iptables -A FOR_PORTT -p udp --dport ".$begin.$limit.$log.$@.":\n";
		}
		if ($prot==1 || $prot==0)
		{
			echo "iptables -A FOR_PORTT -p tcp --dport ".$begin.$limit.$log.$@.":\n";
		}
		echo "echo \"";
		if		($prot == 0)	{ echo "both"; }
		else if	($prot == 1)	{ echo "tcp"; }
		else if	($prot == 2)	{ echo "udp"; }
		echo ",".$port."\" > /var/porttrigger/".$@."\n";
	}
}
set("/runtime/func/portt", $count);
?>
# flush_portt.php <<<
