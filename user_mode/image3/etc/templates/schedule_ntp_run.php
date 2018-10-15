# ntp_run.php >>>
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

echo "xmldbc -k ntp\n";
echo "killall ntpclient\n";


if ($generate_start==1)
{
	if (query("/time/syncwith")!=2)
	{
		echo "echo NTP client is disabled ... > /dev/console\n";
		$ntp_run = "/var/run/ntp_run.sh";
		fwrite( $ntp_run, "#!/bin/sh\n");
		fwrite2($ntp_run, "echo NTP client is disabled... > /dev/console\n");
	}
	else
	{
		$ntps=query("/time/ntpserver/ip");
		$ntpt=query("/time/ntpserver/interval");
		if ($ntpt=="") { $ntpt="604800"; }
		if ($ntps=="")
		{
			echo "echo No NTP server, disable NTP client ... > /dev/console\n";
		}
		else
		{
			$schedule_ntp_connect_counter=query("/runtime/time/ntp/ntp_connect_counter");
			if($schedule_ntp_connect_counter == ""){$schedule_ntp_connect_counter=0;}
			if($schedule_ntp_connect_counter >= 5){$schedule_ntp_connect_counter=4;}
			$schedule_ntp_connect_counter++;
			$ntp_run = "/var/run/schedule_ntp_run.sh";
			fwrite( $ntp_run, "#!/bin/sh\n");
			fwrite2($ntp_run, "echo Run NTP client ... > /dev/console\n");
			fwrite2($ntp_run, "xmldbc -i -s /runtime/time/ntp/state 0\n");
			fwrite2($ntp_run, "ntpclient -h ".$ntps." -i 5 -s > /dev/console\n");
			fwrite2($ntp_run, "if [ $? != 0 ]; then\n");
			fwrite2($ntp_run, "	xmldbc -k ntp\n");
						
			fwrite2($ntp_run, "	xmldbc -i -s /runtime/time/ntp/ntp_connect_counter ".$schedule_ntp_connect_counter."\n");
			if($schedule_ntp_connect_counter < 5)
			{
				fwrite2($ntp_run, "	xmldbc -t \"ntp:9:/etc/templates/wlan.sh restart\"\n");
				fwrite2($ntp_run, "	echo NTP will run in 10 seconds! > /dev/console\n");
			}
			else
			{
				fwrite2($ntp_run, "	/etc/templates/wlan.sh restart\n");
			}
			
			fwrite2($ntp_run, "else\n");
			fwrite2($ntp_run, "	echo NTP will run in ".$ntpt." seconds! > /dev/console\n");
			fwrite2($ntp_run, "	sleep 1\n");
			fwrite2($ntp_run, "	xmldbc -i -s /runtime/time/ntp/state 1\n");
			fwrite2($ntp_run, "	xmldbc -i -s /runtime/timeset 1\n");
			fwrite2($ntp_run, "	UPTIME=`uptime seconly`\n");
			fwrite2($ntp_run, "	xmldbc -i -s /runtime/time/ntp/uptime \"$UPTIME\"\n");
			fwrite2($ntp_run, "	xmldbc -i -s /runtime/time/ntp/ntp_connect_counter 0\n");
			fwrite2($ntp_run, "	sh /var/run/wlan_servd_start.sh\n");
			fwrite2($ntp_run, "fi\n");
			echo "chmod +x ".$ntp_run."\n";
			echo $ntp_run." > /dev/console &\n";
		}
	}
}
?>
# ntp_run.php <<<
