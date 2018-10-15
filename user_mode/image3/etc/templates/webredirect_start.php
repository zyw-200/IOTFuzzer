#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$generate_start=1;
$webredirect_mode = 0;
$webredirect_mode = query("/wlan/inf:1/webredirect/enable");



if($webredirect_mode==1) 
{	
    $ipmode = query("/wan/rg/inf:1/mode");  /*	1:static , 2:dhcp	*/
    if($ipmode == 1)
    {
        $ap_ip=query("/wan/rg/inf:1/static/ip");
    }
    else
    {
        $ap_ip=query("/runtime/wan/inf:1/ip");
    }
	echo "brctl webredirect br0 1 \n";
	echo "brctl setapip br0 ".$ap_ip."\n";
	
}
else
{
	echo "brctl webredirect br0 0 \n";
}

?>
