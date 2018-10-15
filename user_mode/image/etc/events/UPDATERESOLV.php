<?
include "/htdocs/phplib/phyinf.php";

fwrite("w",$START, "#!/bin/sh\n");
fwrite("w",$STOP,  "#!/bin/sh\n");

fwrite("w", "/etc/resolv.conf", "# Auto-Generated\n");
foreach ("/runtime/inf")
{
	$addrtype = query("inet/addrtype");
	if ($addrtype=="ipv4" || $addrtype=="ppp4")
	{
		if (query("inet/".$addrtype."/valid")=="1")
		{
			$def = query("defaultroute");
		//	fwrite("a", $START, "#def: ".$def."\n");
			$uid = query("uid");
			if ($addrtype=="ipv4")		{ $gw = query("inet/".$addrtype."/gateway"); }
			else if ($addrtype=="ppp4")	{ $gw = query("inet/".$addrtype."/peer"); }
			foreach ("inet/".$addrtype."/dns")
			{
				fwrite("a", "/etc/resolv.conf", "nameserver ".$VaLuE."\n");
				if ($def!="" && $def>1)
				{
					if ($gw!="")
					{
						fwrite(a,$START,'ip route add '.$VaLuE.' via '.$gw.' metric '.$def.' table RESOLV\n');
						fwrite(a,$STOP, 'ip route del '.$VaLuE.' via '.$gw.' metric '.$def.' table RESOLV\n');
					}
					else
					{
						fwrite(a,$START,'ip route add '.$VaLuE.' metric '.$def.' table RESOLV\n');
						fwrite(a,$STOP, 'ip route del '.$VaLuE.' metric '.$def.' table RESOLV\n');
					}
				}
				else
				{
					if ($gw!="")
					{
						fwrite(a,$START,'ip route add '.$VaLuE.' via '.$gw.' table RESOLV\n');
						fwrite(a,$STOP, 'ip route del '.$VaLuE.' via '.$gw.' table RESOLV\n');
					}
					else
					{
						fwrite(a,$START,'ip route add '.$VaLuE.' table RESOLV\n');
						fwrite(a,$STOP, 'ip route del '.$VaLuE.' table RESOLV\n');
					}
				}
			}
		}
	}
	if ($addrtype=="ipv6" || $addrtype=="ppp6")
	{
		if (query("inet/".$addrtype."/valid")=="1")
		{
			$def = query("defaultroute");
		//	fwrite("a", $START, "#def: ".$def."\n");
			$uid = query("uid");
			if ($addrtype=="ipv6")		{ $gw = query("inet/".$addrtype."/gateway"); }
			else if ($addrtype=="ppp6")	{ $gw = query("inet/".$addrtype."/peer"); }
			foreach ("inet/".$addrtype."/dns")
			{
				fwrite("a", "/etc/resolv.conf", "nameserver ".$VaLuE."\n");
				if ($def!="" && $def>1)
				{
					if ($gw!="")
					{
						fwrite(a,$START,'ip -6 route add '.$VaLuE.' via '.$gw.' metric '.$def.' table RESOLV\n');
						fwrite(a,$STOP, 'ip -6 route del '.$VaLuE.' via '.$gw.' metric '.$def.' table RESOLV\n');
					}
					else
					{
						fwrite(a,$START,'ip -6 route add '.$VaLuE.' metric '.$def.' table RESOLV\n');
						fwrite(a,$STOP, 'ip -6 route del '.$VaLuE.' metric '.$def.' table RESOLV\n');
					}
				}
				else
				{
					if ($gw!="")
					{
						fwrite(a,$START,'ip -6 route add '.$VaLuE.' via '.$gw.' table RESOLV\n');
						fwrite(a,$STOP, 'ip -6 route del '.$VaLuE.' via '.$gw.' table RESOLV\n');
					}
					else
					{
						fwrite(a,$START,'ip -6 route add '.$VaLuE.' table RESOLV\n');
						fwrite(a,$STOP, 'ip -6 route del '.$VaLuE.' table RESOLV\n');
					}
				}
			}
		}
	}
}

fwrite("a",$START,"service DNS restart\n");
fwrite("a",$START,"exit 0\n");
fwrite("a",$STOP, "exit 0\n");
?>
