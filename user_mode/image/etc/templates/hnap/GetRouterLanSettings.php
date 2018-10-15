HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<? 
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php"; 
$path_inf_lan1 = XNODE_getpathbytarget("", "inf", "uid", "LAN-1", 0);
$path_run_inf_lan1 = XNODE_getpathbytarget("/runtime", "inf", "uid", "LAN-1", 0);
$mask = query($path_run_inf_lan1."/inet/ipv4/mask");
$dhcp_enbled="false";
if (query($path_inf_lan1."/dhcps4") != "")
{
	$dhcp_enbled="true";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetRouterLanSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetRouterLanSettingsResult>OK</GetRouterLanSettingsResult>
      <RouterIPAddress><? echo query($path_run_inf_lan1."/inet/ipv4/ipaddr"); ?></RouterIPAddress>
      <RouterSubnetMask><? echo ipv4int2mask($mask); ?></RouterSubnetMask>
      <DHCPServerEnabled><?=$dhcp_enbled?></DHCPServerEnabled>
    </GetRouterLanSettingsResponse>
  </soap:Body>
</soap:Envelope>
