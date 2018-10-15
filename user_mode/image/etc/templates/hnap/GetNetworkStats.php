HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php";
$path_inf_lan1 = XNODE_getpathbytarget("", "inf", "uid", "LAN-1", 0);
$path_run_lan1 = XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($path_inf_lan1."/phyinf"));
$path_phy_wifi1 = XNODE_getpathbytarget("", "phyinf", "wifi", "WIFI-1",0);
$path_run_wifi1 = XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($path_phy_wifi1."/uid"));
?>

<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetNetworkStatsResponse xmlns="http://purenetworks.com/HNAP1/">
    <GetNetworkStatsResult>OK</GetNetworkStatsResult>
      <Stats>
<?
anchor($path_run_lan1."/stats");
echo "        <NetworkStats>\n";
echo "          <PortName>LAN</PortName>\n";
echo "          <PacketsReceived>".query("rx/packets")."</PacketsReceived>\n";
echo "          <PacketsSent>".query("tx/packets")."</PacketsSent>\n";
echo "          <BytesReceived>".query("rx/bytes")."</BytesReceived>\n";
echo "          <BytesSent>".query("tx/bytes")."</BytesSent>\n";
echo "        </NetworkStats>\n";

anchor($path_run_wifi1."/stats");
echo "        <NetworkStats>\n";
echo "          <PortName>WLAN 802.11</PortName>\n";
echo "          <PacketsReceived>".query("rx/packets")."</PacketsReceived>\n";
echo "          <PacketsSent>".query("tx/packets")."</PacketsSent>\n";
echo "          <BytesReceived>".query("rx/bytes")."</BytesReceived>\n";
echo "          <BytesSent>".query("tx/bytes")."</BytesSent>\n";
echo "        </NetworkStats>\n";
?>      </Stats>
    </GetNetworkStatsResponse>
  </soap:Body>
</soap:Envelope>