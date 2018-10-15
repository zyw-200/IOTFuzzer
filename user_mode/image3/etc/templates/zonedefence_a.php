<? /* vi: set sw=4 ts=4: */
anchor("/wlan/inf:2");
for("/wlan/inf:2/zonedefence_ip/ipaddr")
{
        $ipaddr=query("/wlan/inf:2/zonedefence_ip/ipaddr:".$@);
        echo "iwpriv ".$wlanif_a." add_ip ".$ipaddr."\n";
}
for("/wlan/inf:2/zonedefence_ip/ipv6addr")
{
	$ipv6addr=query("/wlan/inf:2/zonedefence_ip/ipv6addr:".$@);
        echo "iwpriv ".$wlanif_a." add_ipv6 ".$ipv6addr."\n"; 	
}
echo "iwpriv ".$wlanif_a." maccmd 2\n"; //set acl to DENY

?>
