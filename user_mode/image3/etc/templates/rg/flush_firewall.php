# flush_firewall.php >>>
iptables -F FOR_FIREWALL
<? /* vi: set sw=4 ts=4: */

$count=0;
for("/security/di624/firewall/entry")
{
	if (query("enable") == 1 && $wanif!="")
	{
		$proto = query("protocol");
		$iprange = 0;
		if		($proto==1)	{ $protocol="all"; }
		else if	($proto==2)	{ $protocol="tcp"; }
		else if	($proto==3)	{ $protocol="udp"; }
		else if	($proto==4)	{ $protocol="icmp"; }

		/* Reset command first */
		$sipstring	= "";
		$dipstring	= "";
		$portstring	= "";
		$timestring	= "";
		$sifstr		= "";
		$difstr		= "";

		/* gen source ipstring */
		$siptype= query("sourceiprangetype");
		$sbip	= query("sourceipbegin");
		$seip	= query("sourceipend");
		if ($siptype==2 && $sbip!="")				{ $sipstring=" -s ".$sbip; }
		if ($siptype==3 && $sbip!="" && $seip!="")	{ $sipstring=" -m iprange --src-range ".$sbip."-".$seip; $iprange=1;}

		/* gen dest ipstring */
		$diptype= query("destiprangetype");
		$dbip	= query("destipbegin");
		$deip	= query("destipend");
		if ($diptype==2 && $dbip!="")				{ $dipstring=" -d ".$dbip; }
		if ($diptype==3 && $dbip!="" && $deip!="") {
			if ($iprange==0) {$dipstring=" -m iprange --dst-range ".$dbip."-".$deip;}
			else			{$dipstring=" --dst-range ".$dbip."-".$deip;}
		}

		/* gen dest portstring */
		if ($proto==2 || $proto==3)
		{
			$porttype=query("destportrangetype");
			$bport	=query("destportbegin");
			$eport	=query("destportend");
			if ($porttype==2 && $bport!="")					{ $portstring=" --dport ".$bport; }
			if ($porttype==3 && $bport!="" && $eport!="")	{ $portstring=" --dport ".$bport.":".$eport; }
		}

		/* gen timestring */
		if (query("schedule/enable")==1)
		{
			$bday	= query("schedule/beginday");
			$eday	= query("schedule/endday");
			$btime	= query("schedule/begintime");
			$etime	= query("schedule/endtime");
			if ($bday!="" && $eday!="" && $btime!="" && $etime!="")
			{
				echo "DAYSTRING=`dayconvert ".$bday." ".$eday."`\n";
				$timestring=" -m time --timestart ".$btime." --timestop ".$etime." --days $DAYSTRING";
			}
		}
		
		/* gen interface */
		$sif = query("sourceinf");
		if		($sif==1)	{ $sifstr=" -i ".$lanif; }
		else if	($sif==2)	{ $sifstr=" -i ".$wanif; }
		$dif = query("destinf");
		if		($dif==1)	{ $difstr=" -o ".$lanif; }
		else if	($dif==2)	{ $difstr=" -o ".$wanif; }
		$act = query("action");
		if ($act==1)		{ $cmd_tail=" -j ACCEPT\n"; }
		else				{ $cmd_tail=" -j DROP\n";   }	

		/* gen cmd*/
		$cmd_head="iptables -A FOR_FIREWALL".$sifstr.$difstr." -p ";
		if (query("/security/log/droppacketinfo")==1)
		{ 
			$logstring=" -j LOG --log-level info --log-prefix DRP:006:\n";
			echo $cmd_head.$protocol.$sipstring.$dipstring.$portstring.$timestring.$logstring;
		}
		echo $cmd_head.$protocol.$sipstring.$dipstring.$portstring.$timestring.$cmd_tail;
		$count++;
	}
}

set("/runtime/func/firewall", $count);
?>
# flush_firewall.php <<<
