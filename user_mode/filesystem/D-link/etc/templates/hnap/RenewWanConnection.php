HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";

fwrite("w",$ShellPath, "#!/bin/sh\n");
fwrite("a",$ShellPath, "echo \"[$0]-->Renew Wan connect\" > /dev/console\n");
fwrite("a",$ShellPath, "service WAN restart > /dev/console\n");
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <RenewWanConnectionResponse xmlns="http://purenetworks.com/HNAP1/">
      <RenewWanConnectionResult>OK</RenewWanConnectionResult>
    </RenewWanConnectionResponse>
  </soap:Body>
</soap:Envelope>
