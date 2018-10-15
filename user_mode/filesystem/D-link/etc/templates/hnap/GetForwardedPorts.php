<? 
include "/htdocs/phplib/xnode.php";
$path_inf_wan1 = XNODE_getpathbytarget("", "inf", "uid", "WAN-1", 0);
$wan1_nat = query($path_inf_wan1."/nat");
$path_wan1_nat = XNODE_getpathbytarget("nat", "entry", "uid", $wan1_nat, 0); 
?>	
HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetForwardedPortsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetForwardedPortsResult>OK</GetForwardedPortsResult>
      <ForwardedPorts>
<?
	foreach($path_wan1_nat."/portforward/entry")
	{
			echo "        <ForwardedPort>\n";
			echo "          <Enabled>".query("enable")."</Enabled>\n";
			echo "          <Name>".query("description")."</Name>\n";
			echo "          <PrivateIP>".query("internal/hostid")."</PrivateIP>\n";
			echo "          <Protocol>".query("protocol")."</Protocol>\n";
			echo "          <StartExternalPort>".query("external/start")."</StartExternalPort>\n";
			echo "          <EndExternalPort>".query("external/end")."</EndExternalPort>\n";			
		    echo "          <StartInternalPort>".query("internal/start")."</StartInternalPort>\n";
			echo "        </ForwardedPort>\n";
	}
?>      </ForwardedPorts>
    </GetForwardedPortsResponse>
  </soap:Body>
</soap:Envelope>
