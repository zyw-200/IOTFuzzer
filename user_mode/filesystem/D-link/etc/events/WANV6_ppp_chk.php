<?
include "/htdocs/phplib/xnode.php";

echo '#!/bin/sh\n';

/* return 1 if ipaddr is a private IPv4 address else return 0. */
/*
function privatecheck($ipaddr)
{
	$private=0;
	if (isdomain($ipaddr)!="0")
	{
		$a = cut($ipaddr, 0, ".");
		$b = cut($ipaddr, 1, ".");
		$c = cut($ipaddr, 2, ".");
		$d = cut($ipaddr, 3, ".");
		if ($a==10)
		{
			$private=1;
		}
		else if ($a==172)
		{
			if ($b>=16 && $b<=31)
				$private=1;
		}
		else if ($a==192 && $b==168)
		{
			$private=1;
		}
	}
	return $private;
}
*/
//$ip = query("/runtime/services/wandetect/dhcp/".$INF."/ip");
$wantype = query("/runtime/services/wandetect/wantype");
if ($wantype!="PPPoE")
{
	echo 'event WANV6.AUTOCONF.DETECT\n';
}
echo 'exit 0\n';
?>
