#!/bin/sh
<? /* vi: set sw=4 ts=4: */
$limitedadmin = "/var/run/__limitedadmin.sh";
$limitedadmintype = query("/sys/adminlimit/status");
$limitedadminvid  = query("/sys/adminlimit/vlanid");
$vlan_state = query("/sys/vlan_state");
fwrite($limitedadmin, "echo limitedadmin.sh...\n");
	
if ($limitedadmintype>=0 && $limitedadmintype<=3)
{

    fwrite2($limitedadmin, "brctl ladtype br0 ".$limitedadmintype." > /dev/console\n");
	//echo "brctl ladtype br0 ".$limitedadmintype." > /dev/console\n";
}

if ($limitedadminvid>=0 && $limitedadminvid<4096)
{
	fwrite2($limitedadmin, "brctl ladvid br0 ".$limitedadminvid." > /dev/console\n");
	//echo "brctl ladvid br0 ".$limitedadminvid." > /dev/console\n";
}

for("/sys/adminlimit/ipentry/index")
{
	$ippoolstart = query("/sys/adminlimit/ipentry/index:".$@."/ippoolstart".);
	$ippoolend   = query("/sys/adminlimit/ipentry/index:".$@."/ippoolend".);
	if($ippoolstart!="" && $ippoolend!="")
	{
	    fwrite2($limitedadmin, "brctl ladippool br0 ".$@." ".$ippoolstart." ".$ippoolend." > /dev/console\n");
	}else{
	    fwrite2($limitedadmin, "brctl ladippool br0 ".$@." 0.0.0.0 0.0.0.0"." > /dev/console\n");
	}
}
?>
