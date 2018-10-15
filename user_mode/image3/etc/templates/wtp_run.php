#!/bin/sh
echo [$0] $1... > /dev/console
<? /* vi: set sw=4 ts=4: */
$capwap_pid = "/var/run/capwap.pid";
$changed = 0;
$cur_index = 0;
if ($generate_start==1)
{
	if (query("/sys/wtp/enable")==1) {	
		echo "if [ -f ".$capwap_pid." ]; then\n";
		for("/runtime/wtp/acstatic/ip")
		{
			$runtime_ip = query("/runtime/wtp/acstatic/ip:".$@);
			$sys_ip = query("/sys/wtp/acstatic/ip:".$@);
			$cur_index = $@;
			if($runtime_ip != $sys_ip) {
				$changed = 1;
			}
		}
		$cur_index = $cur_index + 1;
		if(query("/sys/wtp/acstatic/ip:".$cur_index) != "" || $changed == 1 || $cur_index == 1){
			echo "echo Stop WTP daemon ... > /dev/console\n";
			echo "kill \`cat ".$capwap_pid."\` > /dev/null 2>&1\n";
			echo "rm -f ".$capwap_pid."\n";
			echo "sleep 1\n";
			echo "echo Start WTP daemon ... > /dev/console\n";
			echo "/usr/sbin/capwap & > /dev/console\n";
			echo "echo $! > ".$capwap_pid."\n";
		}else {
			echo "echo WTP daemon is running... > /dev/console\n";
		}
		echo "else\n";
		echo "echo Start WTP daemon ... > /dev/console\n";
		echo "/usr/sbin/capwap & > /dev/console\n";
		echo "echo $! > ".$capwap_pid."\n";
		echo "fi\n\n";
		del("/runtime/wtp/acstatic");
		for("/sys/wtp/acstatic/ip")
		{
			$sys_ip = query("/sys/wtp/acstatic/ip:".$@);
			set("/runtime/wtp/acstatic/ip:".$@, $sys_ip);
		}
	}
}
else
{
	if (query("/sys/wtp/enable")==0) {	
		echo "if [ -f ".$capwap_pid." ]; then\n";
		echo "echo Stop WTP daemon ... > /dev/console\n";
		echo "kill \`cat ".$capwap_pid."\` > /dev/null 2>&1\n";
		echo "rm -f ".$capwap_pid."\n";
		echo "fi\n\n";
	}
}

?>
