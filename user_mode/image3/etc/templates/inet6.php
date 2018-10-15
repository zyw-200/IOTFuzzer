<?
echo "echo \">>>\$0: Start IPv6 configuration >>>\" > /dev/console;\n";

$router = query("/runtime/router/enable");

if ( $router == 1 ) { $inf = query("/runtime/layout/wanif"); }
else                { $inf = query("/runtime/layout/lanif"); }

$proc = fread("/proc/sys/net/ipv6/conf/".$inf."/disable_ipv6");

if ( $generate_start == 1 )
{
	anchor("/inet/entry:1/ipv6");
	$valid   = query("valid"  );
	$mode    = query("mode"   );
	$ipaddr  = query("ipaddr" );
	$prefix  = query("prefix" );
//	$dns     = query("dns"    );
	$gateway = query("gateway");

	if ( $valid == 0 || $proc == 1 ) //Disable IPv6
	{
	//	echo "echo \"1\" > /proc/sys/net/ipv6/conf/all/disable_ipv6;\n";
		echo "echo \"1\" > /proc/sys/net/ipv6/conf/".$inf."/disable_ipv6;\n";
		echo "echo \"Disable IPv6.\" > /dev/console;\n";
		echo "xmldbc -is /runtime/inet/entry:1/ipv6/valid   0;\n";
		echo "xmldbc -is /runtime/inet/entry:1/ipv6/mode    \"\";\n";
		echo "xmldbc -is /runtime/inet/entry:1/ipv6/ipaddr  \"\";\n";
		echo "xmldbc -is /runtime/inet/entry:1/ipv6/prefix  \"\";\n";
//		echo "xmldbc -is /runtime/inet/entry:1/ipv6/dns     \"\";\n";
		echo "xmldbc -is /runtime/inet/entry:1/ipv6/gateway \"\";\n";
	}
	else //Enable IPv6
	{
		if ( $mode == "auto" ) //autoconfigure
		{
			echo "echo \"Auto mode\" > /dev/console;\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/mode   ".$mode.  ";\n";

			echo "/usr/sbin/inet init;\n";
			
			echo "dhcp6c -c /etc/templates/dhcpv6c.conf br0 &\n";

//			echo "echo \"nameserver ".$dns."\" >> /etc/resolv.conf;\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/dns  \"".$dns.   "\";\n";

			$isIPV6 = 1;
		}
		else if ( $mode == "static" ) //static
		{
			echo "echo \"Static mode\" > /dev/console;\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/mode   ".$mode.  ";\n";

			echo "ip -6 addr add ".$ipaddr."/".$prefix." dev ".$inf.";\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/ipaddr ".$ipaddr.";\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/prefix ".$prefix.";\n";

//			echo "echo \"nameserver ".$dns."\" >> /etc/resolv.conf;\n";
//			echo "xmldbc -is /runtime/inet/entry:1/ipv6/dns   \"".$dns.  "\";\n";

			if ( $gateway != "" )
			{
				echo "ip -6 route add ".$ipaddr."/".$prefix." via ".$gateway.";\n";
			}
			else
			{
				echo "echo \"Without Gateway.\" > /dev/console;\n";
			}

			$isIPV6 = 1;
		}
		else //unknown
		{
			echo "echo \"Unknown IPv6 mode and disable IPv6!!!\" > /dev/console;\n";
			echo "xmldbc -s  /inet/entry:1/ipv6/valid          0;\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/valid  0;\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/mode   \"\";\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/ipaddr \"\";\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/prefix \"\";\n";
//			echo "xmldbc -is /runtime/inet/entry:1/ipv6/dns    \"\";\n";

			$isIPV6 = 0;
		}

		if ( $isIPV6 == 0 )
		{
			echo "echo \"1\" > /proc/sys/net/ipv6/conf/".$inf."/disable_ipv6;\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/valid  0;\n";
			echo "echo -n \"IPv6 Disable.\" > /dev/console;\n";
		}
		else if ( $isIPV6 == 1 )
		{
			echo "echo \"0\" > /proc/sys/net/ipv6/conf/".$inf."/disable_ipv6;\n";
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/valid  1;\n";
			echo "echo -n \"IPv6 Enable.\" > /dev/console;\n";
		}
		else
		{
			echo "xmldbc -is /runtime/inet/entry:1/ipv6/valid  1;\n";
		}

		echo "echo \"(`cat /proc/sys/net/ipv6/conf/".$inf."/disable_ipv6`)\" > /dev/console;\n";
	}
}

if ( $generate_start == 0 )
{
	echo "echo \"Flush IPv6 runtime...\" > /dev/console;\n";
	echo "killall dhcp6c ;\n";
}

echo "echo \"<<< End of IPv6 <<<\" > /dev/console;\n";
?>

