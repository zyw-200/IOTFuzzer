HTTP/1.1 200 OK   
Content-Type: text/xml; charset=utf-8

<? 
echo "\<\?xml version='1.0' encoding='utf-8'\?\>"; 
include "/htdocs/phplib/xnode.php";
$path_inf_wan1 = XNODE_getpathbytarget("", "inf", "uid", "WAN-1", 0);
$wan1_nat = query($path_inf_wan1."/nat");
$path_wan1_nat = XNODE_getpathbytarget("nat", "entry", "uid", $wan1_nat, 0); 
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetPortMappingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetPortMappingsResult>OK</GetPortMappingsResult>
      <PortMappings>
<?
	foreach($path_wan1_nat."/virtualserver/entry")
	{

		echo "        <PortMapping>\n";
		echo "          <Enabled>".query("enable")."</Enabled>\n";
		echo "          <PortMappingDescription>".get("x","description")."</PortMappingDescription>\n";
		echo "          <InternalClient>".query("internal/hostid")."</InternalClient>\n";
		echo "          <PortMappingProtocol>".query("protocol")."</PortMappingProtocol>\n";
		echo "          <ExternalPort>".query("external/start")."</ExternalPort>\n";
		echo "          <InternalPort>".query("internal/start")."</InternalPort>\n";
		echo "        </PortMapping>\n";
	
	}
?>      </PortMappings>
    </GetPortMappingsResponse>
  </soap:Body>
</soap:Envelope>
