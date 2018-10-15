<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";

fwrite(w, $START, "#!/bin/sh\n");
fwrite(w, $STOP,  "#!/bin/sh\n");

/* Create /etc/TZ */
$index = query("/device/time/timezone");
if ($index=="" || $index==0) $index=61;
anchor("/runtime/services/timezone/zone:".$index);

/* Set and Save the TZ status */
set("/runtime/device/timezone/index", $index);
set("/runtime/device/timezone/name",  query("name"));

$TZ = get("s","gen");
if (query("/device/time/dst")=="1")
{
	$TZ = $TZ.get("s","dst");
	set("/runtime/device/timezone/dst", "1");
}
else set("/runtime/device/timezone/dst", "0");
fwrite("w", "/etc/TZ", $TZ."\n");

/* Originally add by Kloat. */
$tmp_date = query("/runtime/device/tmp_date"); del("/runtime/device/tmp_date");
$tmp_time = query("/runtime/device/tmp_time"); del("/runtime/device/tmp_time");
if ($tmp_date!="") set("/runtime/device/date", $tmp_date);
if ($tmp_time!="") set("/runtime/device/time", $tmp_time);
set("/runtime/device/timestate", "SUCCESS");

/* Manually set the date, clear NTP status. */
if ($tmp_date!="" || $tmp_time!="")
{
	set("/runtime/device/ntp/state", "MANUAL");
	set("/runtime/device/ntp/uptime", "");
	set("/runtime/device/ntp/server", "");
	set("/runtime/device/ntp/nexttime", "");
}

/* NTP ... */
$enable = query("/device/time/ntp/enable");
if ($enable==1)
{
	$server = query("/device/time/ntp/server");
	$period = query("/device/time/ntp/period");	if ($period=="") $period="604800";

	if ($server=="") fwrite(a, $START, 'echo "No NTP server, disable NTP client ..." > /dev/console\n');
	else
	{
		$ntp_run = "/var/run/ntp_run.sh";
		fwrite(w, $ntp_run, '#!/bin/sh\n');
		fwrite(a, $ntp_run,
			'echo "Run NTP client ..." > /dev/console\n'.
			'xmldbc -s /runtime/device/ntp/state RUNNING\n'.
			'xmldbc -t "ntp:'.$period.':'.$ntp_run.'"\n'.
			'ntpclient -h '.$server.' -i 5 -s > /dev/console\n'.
			'if [ $? != 0 ]; then\n'.
			'	xmldbc -k ntp\n'.
			'	xmldbc -t "ntp:10:'.$ntp_run.'"\n'.
			'	echo NTP will run in 10 seconds! > /dev/console\n'.
			'else\n'.
			'	echo NTP will run in '.$period.' seconds! > /dev/console\n'.
			'	sleep 1\n'.
			'	xmldbc -s /runtime/device/ntp/state SUCCESS\n'.
			'	UPTIME=`xmldbc -g /runtime/device/uptime`\n'.
			'	xmldbc -s /runtime/device/ntp/uptime "$UPTIME"\n'.
			'	xmldbc -s /runtime/device/ntp/period '.$period.'\n'.
			'	xmldbc -s /runtime/device/ntp/server '.$server.'\n'.
			'	service schedule on\n'.
			'fi\n'
			);
		fwrite(a, $START, 'chmod +x '.$ntp_run.'\n');
		fwrite(a, $START, $ntp_run.' > /dev/console &\n');

		fwrite(a, $STOP,
			'xmldbc -k ntp\n'.
			'killall ntpclient\n'.
			'sleep 1\n'.
			'xmldbc -k ntp\n'.
			'xmldbc -s /runtime/device/ntp/state STOPPED\n'.
			'xmldbc -s /runtime/device/ntp/period ""\n'.
			'xmldbc -s /runtime/device/ntp/nexttime ""\n'
			);
	}
}
else
{
	fwrite(a, $START, 'echo "NTP is disabled ..." > /dev/console\n');
}
?>
