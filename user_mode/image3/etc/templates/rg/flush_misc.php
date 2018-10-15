# flush_misc.php >>>
iptables -t nat -F PRE_MISC
<? /* vi: set sw=4 ts=4: */
if ($wanif != "")
{
	$cmd_head="iptables -t nat -A PRE_MISC -i ".$wanif." -p ";
	$cmd_reply="icmp --icmp-type echo-reply ";
	$cmd_request="icmp --icmp-type echo-request ";

	echo $cmd_head.$cmd_reply."-j ACCEPT\n";

	if (query("/security/firewall/pingallow") == 1)
	{
		echo $cmd_head.$cmd_request."-j ACCEPT\n";
		echo "logger -p 192.0 \"SYS:023\"\n";
	}
	else
	{
		$log = query("/security/log/droppacketinfo");
		if ($log == 1)
		{
			$logstr = "--log-level info --log-prefix 'DRP:003:'";
			echo $cmd_head.$cmd_request."-j LOG ".$logstr."\n";	
		}
		echo $cmd_head.$cmd_request."-j DROP\n";
		echo "logger -p 192.0 \"SYS:022\"\n";
	}

	if (query("/security/firewall/httpallow") == 1)
	{
		$httpremoteip = query(/security/firewall/httpremoteip);
		$httpremoteport = query(/security/firewall/httpremoteport);
		if ($httpremoteip == "0.0.0.0" || $httpremoteip == "")
		{ echo $cmd_head."tcp --dport ".$httpremoteport." -j REDIRECT --to-ports 80\n"; }
		else
		{ echo $cmd_head."tcp --dport ".$httpremoteport." -s ".$httpremoteip." -j REDIRECT --to-ports 80\n"; }
		echo "logger -p 192.0 \"SYS:020[".$httpremoteport."]\"\n";
	}
	else
	{  
		echo "logger -p 192.0 \"SYS:021\"\n";
	}
}
?>
# flush_misc.php <<<
