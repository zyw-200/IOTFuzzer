<? /* vi: set sw=4 ts=4: */
anchor("/wlan/inf:1");
for("/wlan/inf:1/zonedefence_ip/ipaddr")
{
	$ipaddr=query("/wlan/inf:1/zonedefence_ip/ipaddr:".$@);
        echo "iwpriv ".$wlanif_g." add_ip ".$ipaddr."\n"; 	
}
for("/wlan/inf:1/zonedefence_ip/ipv6addr")
{
	$ipv6addr=query("/wlan/inf:1/zonedefence_ip/ipv6addr:".$@);
        echo "iwpriv ".$wlanif_g." add_ipv6 ".$ipv6addr."\n"; 	
}
echo "iwpriv ".$wlanif_g." maccmd 2\n"; //set acl to DENY
   
?>
