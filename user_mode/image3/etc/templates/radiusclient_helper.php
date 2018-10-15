#!/bin/sh
echo [$0] ... > /dev/console
<?
	if($FLAG==2){
	set("/wlan/inf:1/radiusclient_auth_state",2);
	}
	else if($FLAG==1){
	set("/wlan/inf:1/radiusclient_auth_state",1);
	}
	else{
	set("/wlan/inf:1/radiusclient_auth_state",0);
	}
?>

