#!/bin/sh
<?/* vi: set sw=4 ts=4: */
$igmp = "/runtime/services/igmpproxy";
$layout = query("/runtime/device/layout");
$we = query("/device/multicast/wifienhance");
if ($ACTION=="add")
{
	if ($layout=="router")
	{
		if ($GROUP!="")
		{
			$found = 0;
			foreach ($igmp."/group") if ($VaLuE==$GROUP) $found=1;
			if ($found == 0)
			{
				add($igmp."/group", $GROUP);
				echo "iptables -t nat -A PRE.IGMP -d ".$GROUP." -j ACCEPT\n";
			}
		}
	}
}
else if ($ACTION=="del")
{
	if ($layout=="router")
	{
		if ($GROUP!="")
		{
			$found = 0;
			foreach ($igmp."/group") if ($VaLuE==$GROUP) $found=$InDeX;
			if ($found > 0)
			{
				echo "iptables -t nat -D PRE.IGMP -d ".$GROUP." -j ACCEPT\n";
				del($igmp."/group:".$found);
			}
		}
	}
}
else if ($ACTION=="add_member")
{
	if ($we=="1") echo 'echo "add '.$GROUP.' '.$SRC.'" > /proc/net/br_igmpp_'.$IF.'\n';
}
else if ($ACTION=="del_member")
{
	if ($we=="1") echo 'echo "remove '.$GROUP.' '.$SRC.'" > /proc/net/br_igmpp_'.$IF.'\n';
}
else if ($ACTION=="flush")
{
	if ($layout=="router")
	{
		del($igmp);
		echo "iptables -t nat -F PRE.IGMP\n";
		echo "iptables -t nat -A PRE.IGMP -d 224.0.0.1 -j ACCEPT\n";
	}
}
?>
