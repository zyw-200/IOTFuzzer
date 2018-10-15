HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php";
$path_inf_wan1 = XNODE_getpathbytarget("", "inf", "uid", "WAN-1", 0);
$wan1_phyinf = query($path_inf_wan1."/phyinf");
$path_run_phyinf_wan1 = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $wan1_phyinf, 0);
$status = get("x",$path_run_phyinf_wan1."/linkstatus");
if( $status != "0" && $status != "")
{ $statusStr = "CONNECTED"; }
else 
{ $statusStr = "DISCONNECTED"; }

?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<GetWanStatusResponse xmlns="http://purenetworks.com/HNAP1/">
<GetWanStatusResult>OK</GetWanStatusResult>	
<Status><?=$statusStr ?></Status>
</GetWanStatusResponse>
</soap:Body></soap:Envelope>
