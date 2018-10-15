HTTP/1.1 200 OK
Content-Type: text/xml
<?
include "/htdocs/phplib/xnode.php";

if ($AUTHORIZED_GROUP < 0)
{
	$RESULT = "FAIL";
	$REASON = i18n("Permission deny. The user is unauthorized.");
}
else
{
	/* should this be transfered as argument from web ? */
	$UID = "WLAN-1";
	$UID2 = "WLAN-2";
	
	$infp = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $UID, 0);
	$infp2 = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $UID2, 0);
	
	$ifname = query($infp."/name");
	$ifname2 = query($infp2."/name");
	
	$phyp1 = XNODE_getpathbytarget("", "phyinf", "uid", $UID, 0);
	$phyp2 = XNODE_getpathbytarget("", "phyinf", "uid", $UID2, 0);
	$wifi_path1 = XNODE_getpathbytarget("/wifi", "entry", "uid", query($phyp1."/wifi"), 0);
	$wifi_path2 = XNODE_getpathbytarget("/wifi", "entry", "uid", query($phyp2."/wifi"), 0);
	$ssid1 = query($wifi_path1."/ssid");
	$ssid2 = query($wifi_path2."/ssid");
	
	//set extension node
	setattr("/wifi_stat_Aband", "get", "wifi_stat ".$ifname);
	setattr("/wifi_stat", "get", "wifi_stat ".$ifname2);
	
	$DATA1 = query("/wifi_stat_Aband");
	$DATA2 = query("/wifi_stat");
	
	$RESULT = "OK";
	$REASON = "";
}

/*
format should be like this 
<wifi_stat>
	<result>OK</result>
	<reason></reason>
	<Aband>
		<entry>
			<mac></mac>
			<rate></rate>
			<band></band>
		</entry>
		<ssid></ssid>
		<total>1</total>
	</Aband>
	<Gband>
	</Gband>
</wifi_stat>
*/

?>
<?echo '<?xml version="1.0" encoding="utf-8"?>';?>
<wifi_stat>
	<result><?=$RESULT?></result>
	<reason><?=$REASON?></reason>
	<Aband>
		<?=$DATA1?>
		<ssid><?=$ssid1?></ssid>
	</Aband>
	<Gband>
		<?=$DATA2?>
		<ssid><?=$ssid2?></ssid>
	</Gband>
</wifi_stat>
