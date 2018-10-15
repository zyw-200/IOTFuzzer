<? /* vi: set sw=4 ts=4: */

$ipaddr	= query("/runtime/upnpdev/host");
$port	= query("/runtime/upnpdev/port");
$server	= query("/runtime/upnpdev/server");
$maxage	= query("/runtime/upnpdev/maxage");
$vendor	= query("/sys/vendor");
$model	= query("/sys/modelname");
$url	= query("/sys/url");
$ver	= query("/runtime/sys/info/firmwareversion");
$sn		= "01234567";

$modeldesc	= $vendor." ".$model;

/********************************************************************/
/* root device: Wlan Access Point Device */
$dev_root	= "/runtime/upnpdev/root:2";
$udn		= "uuid:".query($dev_root."/uuid");
set($dev_root."/devicetype",	"urn:schemas-upnp-org:device:WLANAccessPoint:1"); anchor($dev_root);
		set("friendlyname",		$model);
		set("manufacturer",		$vendor);
		set("manufacturerurl",	$url);
		set("modeldescription",	$modeldesc);
		set("modelname",		$model);
		set("modelnumber",		"1");
		set("modelurl",			$url);
		set("serialnumber",		$sn);
		set("udn",				$udn);

		/* used by upnpkits */
		set("location",			"http:/\/".$ipaddr.":".$port."/xmldoc/WLANAccessPointDevice.xml");
		set("maxage",			$maxage);
		set("server",			$server);

$serv_root = $dev_root."/service:1";
set($serv_root."/servicetype",	"urn:schemas-upnp-org:service:WLANConfiguration:1"); anchor($serv_root);
		set("serviceid",		"urn:upnp-org:serviceId:WLANConfiguration1");
		set("controlurl",		"/upnpdev.cgi?service=WLANConfiguration1");
		set("eventsuburl",		"/WLANConfiguration1.upnp");
		set("scpdurl",			"http:/\/".$ipaddr.":".$port."/xmldoc/WLANConfiguration1.xml");
		
/* root device: WFADevice */
$dev_root	= "/runtime/upnpdev/root:1";
if (query("/wireless/wps/enable")=="1"/* && query("/runtime/func/wfadev")=="1"*/)
{
	$udn	= "uuid:".query($dev_root."/uuid");
	set($dev_root."/devicetype",	"urn:schemas-wifialliance-org:device:WFADevice:1"); anchor($dev_root);
		set("friendlyname",		$model);
		set("manufacturer",		$vendor);
		set("manufacturerurl",	$url);
		set("modeldescription",	$modeldesc);
		set("modelname",		$model);
		set("modelnumber",		"1");
		set("modelurl",			$url);
		set("serialnumber",		$sn);
		set("udn",				$udn);

		/* used by upnpkits */
		set("location",			"http:/\/".$ipaddr.":".$port."/xmldoc/WFADevice.xml");
		set("maxage",			$maxage);
		set("server",			$server);

	$serv_root = $dev_root."/service:1";
	set($serv_root."/servicetype",	"urn:schemas-wifialliance-org:service:WFAWLANConfig:1"); anchor($serv_root);
		set("serviceid",		"urn:wifialliance-org:serviceId:WFAWLANConfig1");
		set("controlurl",		"/upnpdev.cgi?service=WFAWLANConfig1");
		set("eventsuburl",		"/WFAWLANConfig1.upnp");
		set("scpdurl",			"http:/\/".$ipaddr.":".$port."/xmldoc/WFAWLANConfig.xml");
}
else
{
	del($dev_root);
}
?>
