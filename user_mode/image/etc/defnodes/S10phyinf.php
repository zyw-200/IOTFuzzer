<?
include "/htdocs/phplib/phyinf.php";
$wlan1 = PHYINF_setup("WLAN-1", "wifi", "rai0");
set($wlan1."/media/band", "11GN" );
$wlan2 = PHYINF_setup("WLAN-2", "wifi", "ra0");
set($wlan2."/media/band", "11AN");
?>
