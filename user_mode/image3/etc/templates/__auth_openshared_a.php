<? /* vi: set sw=4 ts=4: */
anchor("/wlan/inf:2");
$auth_mode	= query("authentication");
//$keylength	= query("keylength");
$defkey		= query("defkey");
if($defkey==1){
	$keyformat  = query("wepkey:1/keyformat");
}
else if($defkey==2){
	$keyformat  = query("wepkey:2/keyformat");
}
else if($defkey==3){
	$keyformat  = query("wepkey:3/keyformat");
}
else if($defkey==4){
	$keyformat  = query("wepkey:4/keyformat");
}
else{
	$keyformat  = query("wepkey:1/keyformat");
}

$wepmode	= query("wpa/wepmode");
//$wlan_ap_operate_mode = query("/wireless/ap_mode");
/* Set to open mode, this will force driver to initialize the key table. */
echo "iwpriv ".$wlanif_a." authmode 1\n";

if ($wepmode==1)
{
	/*
	 *	For ASCII string:
	 *		iwconfig ath0 key s:"ascii" [1]
	 *	For Hex string:
	 *		iwconfig ath0 key "1234567890" [1]
	 */
	if ($keyformat==1)	{ $iw_keystring="s:\"".get("s","wepkey:".$defkey)."\" [".$defkey."]";}
	else				{ $iw_keystring="\"".query("wepkey:".$defkey)."\" [".$defkey."]"; }
	echo "iwconfig ".$wlanif_a." key ".$iw_keystring."\n";
	if ($auth_mode==1)	{ echo "iwpriv ".$wlanif_a." authmode 2; iwconfig ".$wlanif_a." key ".$iw_keystring."\n"; }
}
?>
