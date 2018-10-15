# flush_blocking >>>
<? /* vi: set sw=4 ts=4: */ ?>
iptables -F INP_BLOCKING
iptables -F FOR_BLOCKING
rmmod ipt_string > /dev/null 2>&1
insmod /lib/modules/ipt_string.o
<?
/* URL Blocking */
$log=query("/security/log/droppacketinfo");

$logtgt=" -j LOG --log-level info --log-prefix";
$urlcount=0;
if (query("/security/urlblocking/enable") == 1)
{
	$log_prefix=" 'DRP:007:'\n";
	if (query("/security/urlblocking/action")=="1")
	{
		for ("/security/urlblocking/string")
		{
			$target_url=query("/security/urlblocking/string:".$@);
			if ($target_url!="")
			{
				echo "iptables -A FOR_BLOCKING -m string --url ".$target_url." -j ACCEPT\n";
				$urlcount++;
			}
		}
		/*
		if ($log == 1) {echo "iptables -A FOR_BLOCKING -p tcp --dport 80 ".$logtgt.$log_prefix;}
		echo "iptables -A FOR_BLOCKING -p tcp --dport 80 -j DROP\n";
		*/
		if ($log == 1) {echo "iptables -A FOR_BLOCKING -p tcp --dport 80 -m string --http_req ".$logtgt.$log_prefix;}
		echo "iptables -A FOR_BLOCKING -p tcp --dport 80 -m string --http_req -j DROP\n";
		$urlcount++;
	}
	else
	{
		for ("/security/urlblocking/string")
		{
			$target_url=query("/security/urlblocking/string:".$@);
			if ($target_url != "")
			{
				if ($log == 1) {echo "iptables -A FOR_BLOCKING -m string --url ".$target_url.$logtgt.$log_prefix;}
				echo "iptables -A FOR_BLOCKING -m string --url ".$target_url." -j DROP\n";
				$urlcount++;
			}
		}
		echo "logger -p 192.0 \"SYS:011\"\n";
	}
}
else
{
	echo "logger -p 192.0 \"SYS:010\"\n";
}
set("/runtime/func/urlfilter",$urlcount);

anchor("/runtime/func");
$ftp=query("ftp");
$fastnat=query("fastnat");
$urlfilter=query("urlfilter");
if($urlfilter>0 && $fastnat==1)             { echo "rmmod sw_tcpip\n"; set("/runtime/func/fastnat","0"); }
if($urlfilter==0 && $fastnat==0 && $ftp==0)  { echo "insmod /lib/modules/sw_tcpip.o\n"; set("/runtime/func/fastnat","1"); }

/* Domain Blocking */
$dblk=query("/security/domainblocking/enable");
$log_prefix=" 'DRP:007:'\n";
if ($dblk == 1)
{
	for ("/security/domainblocking/deny")
	{
		$path="/security/domainblocking/deny:".$@;
		if ($log == 1)
		{
			echo "iptables -A INP_BLOCKING -m string --dns ".query($path).$logtgt.$log_prefix;
			echo "iptables -A FOR_BLOCKING -m string --dns ".query($path).$logtgt.$log_prefix;
		}
		echo "iptables -A INP_BLOCKING -m string --dns ".query($path)." -j DROP\n";
		echo "iptables -A FOR_BLOCKING -m string --dns ".query($path)." -j DROP\n";
	}
	echo "logger -p 192.0 \" SYS:015 \"\n";
}
else if ($dblk == 2)
{
	for ("/security/domainblocking/allow")
	{
		$path="/security/domainblocking/allow:".$@;
		echo "iptables -A INP_BLOCKING -m string --dns ".query($path)." -j ACCEPT\n";
		echo "iptables -A FOR_BLOCKING -m string --dns ".query($path)." -j ACCEPT\n";
	}
	if ($log == 1)
	{
		echo "iptables -A INP_BLOCKING".$logtgt.$log_prefix;
		echo "iptables -A FOR_BLOCKING".$logtgt.$log_prefix;
	}
	echo "iptables -A INP_BLOCKING -j DROP\n";
	echo "iptables -A FOR_BLOCKING -j DROP\n";
	echo "logger -p 192.0 \" SYS:014 \"\n";
}
else
{
	echo "logger -p 192.0 \"SYS:013\"\n";
}
?>
# flush_blocking <<< 
