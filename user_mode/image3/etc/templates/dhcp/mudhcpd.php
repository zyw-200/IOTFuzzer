#!/bin/sh
echo [$0] ... > /dev/console
<?
$multiinstances = query("/lan/dhcp/server/multiinstances");

if($generate_start==1)
{
	if($multiinstances != "1")
	{
		$dhcpd_if    = query("/runtime/layout/lanif");
		$dhcpd_pidf  = "/var/run/mudhcpd-".$dhcpd_if.".pid";
		$dhcpd_conf  = "/var/run/mudhcpd-".$dhcpd_if.".conf";
		$dhcpd_lease = "/var/run/mudhcpd-".$dhcpd_if.".lease";
		$dhcpd_patch = "/var/run/mudhcppatch-".$dhcpd_if.".pid";

		echo "echo \"Start DHCP server (".$dhcpd_if.") ...\" > /dev/console\n";
		if(query("/lan/dhcp/server/enable") != "1")
		{
			echo "echo DHCP server is disabled! > /dev/console\n";
			echo "brctl dhcp_server_enable ".$dhcpd_if." 0 > /dev/console\n";
		}
		else
		{
			anchor("/lan/dhcp/server/pool:1");
			$ipstr    = query("startip");
			$ipend    = query("endip");
			$dmain    = query("domain");
			$wins0    = query("primarywins");
			$wins1    = query("secondarywins");
			$ltime    = query("leasetime");
        	$dhcpdns1 = query("dns1");
			$gateway  = query("gateway");
			$netmask  = query("netmask");

			if($ltime == "") { $ltime = "8640"; }
			if($dmain == "") { $dmain = query("/runtime/wan/inf:1/domain"); }

			fwrite( $dhcpd_conf, "start ".		$ipstr."\n");
			fwrite2($dhcpd_conf, "end ".		$ipend."\n");
			fwrite2($dhcpd_conf, "interface ".	$dhcpd_if."\n");
			fwrite2($dhcpd_conf, "lease_file ".	$dhcpd_lease."\n");
			fwrite2($dhcpd_conf, "pidfile ".	$dhcpd_pidf."\n");
			fwrite2($dhcpd_conf, "auto_time ".	"10\n");
			fwrite2($dhcpd_conf, "opt lease ".	$ltime."\n");
			fwrite2($dhcpd_conf, "opt dns ".    $dhcpdns1."\n");
			if($dmain!="")    { fwrite2($dhcpd_conf, "opt domain ".	$dmain.   "\n"); }
			if($netmask!="")  { fwrite2($dhcpd_conf, "opt subnet ". $netmask. "\n"); }
			if($gateway!="")  { fwrite2($dhcpd_conf, "opt router ". $gateway. "\n"); }
			if($wins0!="")    { fwrite2($dhcpd_conf, "opt wins ".   $wins0.   "\n"); }
			if($wins1!="")    { fwrite2($dhcpd_conf, "opt wins ".   $wins1.   "\n"); }

			if(query("staticdhcp/enable") == 1)
			{
				for("/lan/dhcp/server/pool:1/staticdhcp/entry")
				{
					if(query("enable") == 1)
					{
						$ip  = query("ip");
						$mac = query("mac");
						fwrite2($dhcpd_conf, "static ".$ip." ".$mac."\n");
					}
				}
			}

			echo "udhcpd ".$dhcpd_conf." &\n";
			echo "dhcpxmlpatch -f ".$dhcpd_lease." &\n";
			echo "echo $! > ".$dhcpd_patch."\n";
			echo "brctl dhcp_server_enable ".$dhcpd_if." 1 > /dev/console\n";
		}
	}
	else
	{
		$bindif = "br0";

		if(query("/lan/dhcp/server/enable") != "1")
		{
			echo "echo DHCP server is disabled! > /dev/console\n";
			echo "brctl dhcp_server_enable ".$bindif." 0 > /dev/console\n";
		}
		else
		{
			if(query("/wlan/inf:1/multi/state") != "1")
			{
				exit;
			}

			for("/lan/dhcp/server/pool")
			{
				$idx = $@ - 1;
				if(query("/wlan/inf:1/multi/index:".$idx."/state") == "1" || $idx == "0")
				{
					if(query("/lan/dhcp/server/pool:".$@."/enable") == "1")
					{
						//$idx = $@ - 1;
						$dhcpd_if    = "ath".$idx;
						$dhcpd_pidf  = "/var/run/mudhcpd-".$dhcpd_if.".pid";
						$dhcpd_conf  = "/var/run/mudhcpd-".$dhcpd_if.".conf";
						$dhcpd_lease = "/var/run/mudhcpd-".$dhcpd_if.".lease";
						$dhcpd_patch = "/var/run/mudhcppatch-".$dhcpd_if.".pid";

						echo "echo \"Start DHCP server (".$dhcpd_if.") ...\" > /dev/console\n";

						$ipstr    = query("startip");
						$ipend    = query("endip");
						$dmain    = query("domain");
						$wins0    = query("primarywins");
						$wins1    = query("secondarywins");
						$ltime    = query("leasetime");
        				$dhcpdns1 = query("dns1");
						$gateway  = query("gateway");
						$netmask  = query("netmask");

						if($ltime == "") { $ltime = "8640"; }
						if($dmain == "") { $dmain = query("/runtime/wan/inf:1/domain"); }

						fwrite( $dhcpd_conf, "start ".		$ipstr.       "\n");
						fwrite2($dhcpd_conf, "end ".		$ipend.       "\n");
						fwrite2($dhcpd_conf, "interface ".	$bindif.      "\n");
						fwrite2($dhcpd_conf, "wlanif ".     $dhcpd_if.    "\n");
						fwrite2($dhcpd_conf, "lease_file ".	$dhcpd_lease. "\n");
						fwrite2($dhcpd_conf, "pidfile ".	$dhcpd_pidf.  "\n");
						fwrite2($dhcpd_conf, "auto_time ".	"10".         "\n");
						fwrite2($dhcpd_conf, "opt lease ".	$ltime.       "\n");
						fwrite2($dhcpd_conf, "opt dns ".    $dhcpdns1.    "\n");
						if($dmain!="")    { fwrite2($dhcpd_conf, "opt domain ".	$dmain.   "\n"); }
						if($netmask!="")  { fwrite2($dhcpd_conf, "opt subnet ". $netmask. "\n"); }
						if($gateway!="")  { fwrite2($dhcpd_conf, "opt router ". $gateway. "\n"); }
						if($wins0!="")    { fwrite2($dhcpd_conf, "opt wins ".   $wins0.   "\n"); }
						if($wins1!="")    { fwrite2($dhcpd_conf, "opt wins ".   $wins1.   "\n"); }

						if(query("staticdhcp/enable") == 1)
						{
							for("/lan/dhcp/server/pool:".$@."/staticdhcp/entry")
							{
								if(query("enable") == 1)
								{
									$ip  = query("ip");
									$mac = query("mac");
									fwrite2($dhcpd_conf, "static ".$ip." ".$mac."\n");
								}
							}
						}

						echo "udhcpd ".$dhcpd_conf." &\n";
						echo "dhcpxmlpatch -f ".$dhcpd_lease." &\n";
						echo "echo $! > ".$dhcpd_patch."\n";
						echo "brctl dhcp_server_enable ".$bindif." 1 > /dev/console\n\n";
					}
				}
			}
		}
	}
}
else
{
	if($multiinstances != "1")
	{
		$dhcpd_if = query("/runtime/layout/lanif");
		$dhcpd_patch = "/var/run/mudhcppatch-".$dhcpd_if.".pid";
		$dhcpd_pidf  = "/var/run/mudhcpd-".$dhcpd_if.".pid";

		echo "echo \"Stop DHCP server (".$dhcpd_if.") ...\" > /dev/console\n";
		echo "brctl dhcp_server_enable ".$dhcpd_if." 0 > /dev/console\n";

		echo "if [ -f ".$dhcpd_patch." ]; then\n";
		echo "	pid=`cat ".$dhcpd_patch."`\n";
		echo "	if [ $pid != 0 ]; then\n";
		echo "		kill -9 $pid > /dev/console 2>&1\n";
		echo "	fi\n";
		echo "	rm -f ".$dhcpd_patch."\n";
		echo "fi\n";

		echo "if [ -f ".$dhcpd_pidf." ]; then\n";
		echo "	pid=`cat ".$dhcpd_pidf."`\n";
		echo "	if [ $pid != 0 ]; then\n";
		echo "		kill -9 $pid > /dev/console 2>&1\n";
		echo "	fi\n";
		echo "	rm -f ".$dhcpd_pidf."\n";
		echo "fi\n\n";
	}
	else
	{
		$bindif = "br0";

		if(query("/wlan/inf:1/multi/state") != "1")
		{
			exit;
		}

		for("/lan/dhcp/server/pool")
		{
			$idx = $@ - 1;
			if(query("/wlan/inf:1/multi/index:".$idx."/state") == "1" || $idx == "0")
			{
				if(query("/lan/dhcp/server/pool:".$@."/enable") == "1")
				{
					$dhcpd_if = "ath".$idx;
					$dhcpd_patch = "/var/run/mudhcppatch-".$dhcpd_if.".pid";
					$dhcpd_pidf  = "/var/run/mudhcpd-".$dhcpd_if.".pid";

					echo "echo \"Stop DHCP server (".$dhcpd_if.") ...\" > /dev/console\n";
					echo "brctl dhcp_server_enable ".$bindif." 0 > /dev/console\n";

					echo "if [ -f ".$dhcpd_patch." ]; then\n";
					echo "	pid=`cat ".$dhcpd_patch."`\n";
					echo "	if [ $pid != 0 ]; then\n";
					echo "		kill -9 $pid > /dev/console 2>&1\n";
					echo "	fi\n";
					echo "	rm -f ".$dhcpd_patch."\n";
					echo "fi\n";

					echo "if [ -f ".$dhcpd_pidf." ]; then\n";
					echo "	pid=`cat ".$dhcpd_pidf."`\n";
					echo "	if [ $pid != 0 ]; then\n";
					echo "		kill -9 $pid > /dev/console 2>&1\n";
					echo "	fi\n";
					echo "	rm -f ".$dhcpd_pidf."\n";
					echo "fi\n\n";
				}
			}
		}
	}
}
?>
