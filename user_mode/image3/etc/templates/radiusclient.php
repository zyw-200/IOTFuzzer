<?
	$radius_ip = query("/wlan/inf:1/radius_ip");
	$radius_pass = query("/wlan/inf:1/radius_password");
	$radiusclient_username = query("/wlan/inf:1/radiusclient_username");
	$radiusclient_pass = query("/wlan/inf:1/radiusclient_password");
	$radius_conf  = "/var/run/radious.conf";
	$servers = "/var/run/servers";
	fwrite($servers,  "".$radius_ip." ".$radius_pass."\n");

	fwrite($radius_conf,  "auth_order radius,local\n");
	fwrite2($radius_conf, "login_tries 1\n");
	fwrite2($radius_conf, "login_timeout 10\n");
	fwrite2($radius_conf, "nologin \n");
	fwrite2($radius_conf, "authserver ".$radius_ip."\n");
	fwrite2($radius_conf, "acctserver ".$radius_ip."\n");
	fwrite2($radius_conf, "servers /var/run/servers\n");
	fwrite2($radius_conf, "dictionary /usr/sbin/dictionary\n");	
	fwrite2($radius_conf, "login_radius \n");
	fwrite2($radius_conf, "seqfile \n");
	fwrite2($radius_conf, "mapfile /usr/sbin/port-id-map\n");
	fwrite2($radius_conf, "default_realm \n");
	fwrite2($radius_conf, "radius_timeout 10\n");
	fwrite2($radius_conf, "radius_retries 3\n");
	fwrite2($radius_conf, "bindaddr *\n");
	fwrite2($radius_conf, "username ".$radiusclient_username."\n");
	fwrite2($radius_conf, "password ".$radiusclient_pass."\n");
	
?>

