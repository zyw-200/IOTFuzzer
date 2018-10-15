<?
anchor("/sys");
set("web/sysname",	"DAP-2230");
set("modelname",	"DAP-2230");
set("devicename",	"D-Link Wireless Access Point");
set("modeldescription",	"Wireless N Access Point");
set("vendor",		"D-Link");
set("url",		"http:\/\/www.dlink.com");
set("ipaddr",		"192.168.0.1");
set("netmask",		"255.255.255.0");
set("startip",		"192.168.0.100");
set("endip",		"192.168.0.199");
set("ssid",		"dlink");

set("locale",					"en");
set("authtype",		"s");

set("/function/no_jumpstart", "1");
set("/function/normal_g", "1");
set("/function/bridgemode", "1");

set("/nat/vrtsrv/max_rules",		"10");
set("/nat/porttrigger/max_rules",	"10");
set("/security/macfilter/max_rules",	"10");
set("/security/urlblocking/max_rules",	"20");

set("/runtime/func/static_dhcp",    "1");

/* web_Upnp , added by Enos, 2008/01/31, setnodes.php */
/*set("/function/httpd_upnp", "1");*/

?>
