<module>
	<service>RUNTIME.TIME</service>
	<runtime>
		<device>
			<date><?echo query("/runtime/device/date");?></date>
			<time><?echo query("/runtime/device/time");?></time>
			<timestate><?echo query("/runtime/device/timestate");?></timestate>
			<uptime><?echo get("TIME","/runtime/device/uptime");?></uptime>
			<uptimes><?echo query("/runtime/device/uptime");?></uptimes>
			<rfc1123time><?echo query("/runtime/device/rfc1123time");?></rfc1123time>
			<ntp><?
					$uptime = query("/runtime/device/ntp/uptime");
					$period = query("/runtime/device/ntp/period");
					if ($uptime != "" && $period != "") $nexttime = $uptime + $period;
					else $nexttime = "";
					set("/runtime/device/ntp/nexttime", $nexttime);

				?>
				<state><?echo query("/runtime/device/ntp/state");?></state>
				<server><?echo query("/runtime/device/ntp/server");?></server>
				<uptime><?echo get("TIME", "/runtime/device/ntp/uptime");?></uptime>
				<uptimes><?echo query("/runtime/device/ntp/uptime");?></uptimes>
				<period><?echo query("/runtime/device/ntp/period");?></period>
				<nexttime><? if ($nexttime != "") echo get("TIME", "/runtime/device/ntp/nexttime");?></nexttime>
				<nexttimes><?echo query("/runtime/device/ntp/nexttime");?></nexttimes>
			</ntp>
			<timezone>
				<index><?
				include "/htdocs/phplib/time.php";
				$dst = query("/runtime/device/timezone/dst");
				$tzname = query("/runtime/device/timezone/name");
				$localename = TIME_i18n_tzname($tzname);
				echo query("/runtime/device/timezone/index");
				?></index>
				<name><?echo escape("x", $tzname);?></name>
				<localename><?echo escape("x",$localename);?></localename>
				<dst><?=$dst?></dst>
			</timezone>
		</device>
	</runtime>
	<ACTIVATE>ignore</ACTIVATE>
</module>
