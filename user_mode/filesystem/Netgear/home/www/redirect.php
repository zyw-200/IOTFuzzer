<?php
if(preg_match("/--/",$currentIpAddress))
{
	$ipv6addr = explode('/',$currentIpAddress); 
	$ipv6addr = str_replace('-',':',$ipv6addr[0]);
	if ($_SERVER['SERVER_PORT']=='443') {
		$url = 'https://['.$ipv6addr.']/';
	}
	else {
		$url = 'http://['.$ipv6addr.']/';
	}
}
else if ($_SERVER['SERVER_PORT']=='443') {
	$url = 'https://'.$currentIpAddress.'/';
}
else {
	$url = 'http://'.$currentIpAddress.'/';
}
echo <<<EOHTML
	<html>
	<head>
	</head>
	<body onload="top.location.href='$url';">
		<p align='center'>You will now be redirected to the new IP address<br />
		<a href='$url' target="_TOP">Click here if you are not automatically redirected to the new IP address</a></p>
	</body>
	</html>
EOHTML;
	die();
?>
