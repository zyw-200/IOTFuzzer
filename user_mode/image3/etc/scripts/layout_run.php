#!/bin/sh
echo [$0] $1 ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$bridge = query("/bridge");
if ($bridge!=1)	{ $router_on = 1; }
else			{ $router_on = 0; }

if ($router_on==1)
{
	if ($generate_start==1)
	{
		echo "echo Start router layout ...\n";
		if (query("/runtime/router/enable")==1)
		{
			echo "echo Already in router mode!\n";
			exit;
		}
		echo "ifconfig eth0 up\n";
		echo "ifconfig br0 up\n";
		echo "rgdb -i -s /runtime/router/enable 1\n";
	}
	else
	{
		echo "echo Stop router layout ...\n";
		echo "ifconfig eth0 down\n";
		echo "rgdb -i -s /runtime/router/enable \"\"\n";
	}
}
else
{
	if ($generate_start==1)
	{
		echo "echo Start bridge layout ...\n";
		if (query("/runtime/router/enable")==0)
		{
			echo "echo Already in bridge mode!\n";
			exit;
		}
		echo "ifconfig eth0 up\n";
		echo "brctl addif br0 eth0\n";
		echo "echo \"2\" > /proc/sys/net/ipv6/conf/br0/accept_dad;\n";
		echo "v=`xmldbc -g /inet/entry:1/ipv6/valid`;\n";
		echo "if [ \$v = 1 ]; then\n";
		echo "	echo \"0\" > /proc/sys/net/ipv6/conf/br0/disable_ipv6;\n";
		echo "else\n";
		echo "	echo \"1\" > /proc/sys/net/ipv6/conf/br0/disable_ipv6;\n";
		echo "fi\n";
		echo "sleep 1\n";
		echo "ifconfig br0 up\n";
		echo "rgdb -i -s /runtime/router/enable 0\n";
	}
	else
	{
		echo "echo Stop bridge layout ...\n";
		echo "brctl delif br0 eth0\n";
		echo "rgdb -i -s /runtime/router/enable \"\"\n";
	}
}
?>
