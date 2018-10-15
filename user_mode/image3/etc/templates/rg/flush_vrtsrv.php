# flush_vrtsrv.php >>>
<? /* vi: set sw=4 ts=4: */ ?>
iptables -t nat -F PRE_VRTSRV
iptables -t nat -F PST_VRTSRV
iptables -t mangle -F PRE_MARK 
<?
$count=0;
$idcount=0;
$ftpcount=0;
$head_man="iptables -t mangle -A PRE_MARK";
$head_nat="iptables -t nat -A PRE_VRTSRV";


//$vrtsrv=query("/nat/vrtsrv/enable");
if ($wanip!="")
{
	for("/nat/vrtsrv/entry")
	{
		if (query("enable") == 1)
		{
			$protocol	= query("protocol");
			$priip		= query("privateip");
			$priport	= query("privateport");
			$pubport	= query("publicport");
			$priport2	= query("privateport2");
			$pubport2	= query("publicport2");
						
			if ($priport==21) { $ftpcount++; }
			//gen sch for virtual server di524 di624 wbr2310
			//            portforwarding wbr2310
			$timestring="";
			if(query("schedule/enable")!=0) 
			{
				$uniqueid=query("schedule/uniqueid");
				if ($uniqueid=="") { $tmp_path="/nat/vrtsrv/entry:".$@."/schedule"; }
				else  { for("/sys/schedule/entry") { if($uniqueid==query("id")) { $tmp_path="/sys/schedule/entry:".$@; } } }
			}
			if($tmp_path!="")
			{
				$bday	= query($tmp_path."/beginday");
				$eday	= query($tmp_path."/endday");
				$btime	= query($tmp_path."/begintime");
				$etime	= query($tmp_path."/endtime");
				if ($bday!="" && $eday!="" && $btime!="" && $etime!="")
				{
					echo "DAYSTRING=`dayconvert ".$bday." ".$eday."`\n";
					$timestring=" -m time --timestart ".$btime." --timestop ".$etime." --days $DAYSTRING";
				}
			}						
			
			//gen port type for di524 di624 wbr1310 wbr2310
			if($priport2=="" && $pubport2=="")		
			{ 
				$privateport=$priport; 
				$publicport=" --dport ".$pubport; 
			}
            else
			{
				$privateport=$priport."-".$priport2;
				$publicport=" -m mport --ports ".$pubport.":".$pubport2;
			}
			
			$tail_man=" -j MARK --set-mark 1\n";
			$tail_nat=" -j DNAT --to ".$priip.":".$privateport."\n";
			//gen command
			if($protocol==0 || $protocol == 1)
			{
				echo $head_man." -p tcp".$publicport.$tail_man;
				echo $head_nat." -p tcp".$publicport." -d ".$wanip.$timestring.$tail_nat;
				$count++;
			}
			if($protocol==0 || $protocol == 2)
			{
				echo $head_man." -p udp".$publicport.$tail_man;
				echo $head_nat." -p udp".$publicport." -d ".$wanip.$timestring.$tail_nat;
				$count++;
			}
			
		}
	}
	echo "iptables -t nat -A PST_VRTSRV -j SNAT --to-source ".$wanip."\n";


	if		($ftpcount==1) { set("/runtime/func/ftp","1"); }
	else if ($ftpcount==0) { set("/runtime/func/ftp","0"); }

	anchor("/runtime/func");
	$ftp=query("ftp");
	$fastnat=query("fastnat");
	$urlfilter=query("urlfilter");
	if($ftp==1 && $fastnat==1)					{ echo "rmmod sw_tcpip\n"; set("/runtime/func/fastnat","0"); }
	if($ftp==0 && $fastnat==0 && $urlfilter==0) { echo "insmod /lib/modules/sw_tcpip.o\n"; set("/runtime/func/fastnat","1"); }

set("/runtime/func/vrtsrv", $count);
}
?>
# flush_vrtsrv.php <<<
