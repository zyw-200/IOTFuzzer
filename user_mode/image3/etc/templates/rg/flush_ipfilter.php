# flush_ipfilter.php >>>
iptables -F FOR_IPFILTER
<? /* vi: set sw=4 ts=4: */

$count=0;
for("/security/di624/ipfilter/entry")
{
	if (query("enable") == 1)
	{
		/* get protocol first */
		$proto=query("protocol");
		if		($proto==1)	{ $protocol="all"; }
		else if	($proto==2)	{ $protocol="tcp"; }
		else if	($proto==3)	{ $protocol="udp"; }
		else if	($proto==4)	{ $protocol="icmp";}

		$ipstring = "";
		$portstring = "";
		$timestring="";

		/* gen ipstring */
		$iptype	= query("sourceiprangetype");
		$bip	= query("sourceipbegin");
		$eip	= query("sourceipend");
		if ($iptype==2 && $bip!="")				{ $ipstring=" -s ".$bip; }
		if ($iptype==3 && $bip!="" && $eip!="")	{ $ipstring=" -m iprange --src-range ".$bip."-".$eip; }

		if ($proto == 2 || $proto == 3)
		{
			/* gen portstring */
			$porttype	= query("destportrangetype");
			$bport		= query("destportbegin");
			$eport		= query("destportend");
			if ($porttype==2 && $bport!="")					{ $portstring=" --dport ".$bport; }
			if ($porttype==3 && $bport!="" && $eport!="")	{ $portstring=" --dport ".$bport.":".$eport; }
		}
		/* gen timestring */
		if (query("schedule/enable")==1)
		{
			$bday=query("schedule/beginday");
			$eday=query("schedule/endday");
			$btime=query("schedule/begintime");
			$etime=query("schedule/endtime");
			if ($bday!="" && $eday!="" && $btime!="" && $etime!="")
			{
				echo "DAYSTRING=`dayconvert ".$bday." ".$eday."`\n";
				$timestring=" -m time --timestart ".$btime." --timestop ".$etime." --days $DAYSTRING";
			}
		}

		/* gen cmd*/
		$cmd_head="iptables -A FOR_IPFILTER -i ".$lanif." -p ";
		$cmd_tail=" -j DROP\n";
		$log = query("/security/log/droppacketinfo");
		if($log==1) 
		{ 
			$logstring=" -j LOG --log-level info --log-prefix DRP:005:\n"; 
			echo $cmd_head.$protocol.$ipstring.$portstring.$timestring.$logstring;
		}
		echo $cmd_head.$protocol.$ipstring.$portstring.$timestring.$cmd_tail;
		$count++;
	}
}
set("/runtime/func/ipfilter", $count);
?>
# flush_ipfilter.php <<<
