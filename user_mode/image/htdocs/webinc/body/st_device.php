<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Device Information");?></h1>
	<p>
		<?echo i18n("All of your Internet and network connection details are displayed on this page. The firmware version is also displayed here.");?>
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("General");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Time");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_time"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Firmware Version");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/device/firmwareversion").' '.query("/runtime/device/firmwarebuilddate");?></span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox" id="wan_ethernet_block" style="display:none">
    <h2><?echo i18n("WAN");?></h2>
    <div class="textinput">
        <span class="name"><?echo i18n("Connection Type");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wantype"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Cable Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wancable"></span>
    </div>
    <div class="textinput" id="wan_failover_block" style="display:none;">
        <span class="name"><?echo i18n("WAN Failover Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wan_failover"></span>
    </div>    
    <div class="textinput">
        <span class="name"><?echo i18n("Network Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_networkstatus"></span>
    </div>
    <div class="textinput" id="st_wan_dhcp_action" style="display:none;">
        <span class="name"></span>
        <span class="delimiter"></span>
        <span class="value">
            <input type="button" id="st_wan_dhcp_renew" value="<?echo i18n("Renew");?>" onClick="PAGE.DHCP_Renew();"/>&nbsp;&nbsp;
            <input type="button" id="st_wan_dhcp_release" value="<?echo i18n("Release");?>" onClick="PAGE.DHCP_Release();"/>  
        </span>
    </div> 
    <div class="textinput" id="st_wan_ppp_action" style="display:none;">
        <span class="name"></span>
        <span class="delimiter"></span>
        <span class="value">
            <input type="button" id="st_wan_ppp_connect" value="<?echo i18n("Connect");?>" onClick="PAGE.PPP_Connect();"/>&nbsp;&nbsp;
            <input type="button" id="st_wan_ppp_disconnect" value="<?echo i18n("Disconnect");?>" onClick="PAGE.PPP_Disconnect();"/>  
        </span>
    </div>    
	<div class="textinput">
        <span class="name"><?echo i18n("Connection Up Time");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_connection_uptime"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("MAC Address");?></span>
	    <span class="delimiter">:</span>
	    <span class="value" id="st_wan_mac"></span>
    </div>
    <div class="textinput">
        <span class="name" id= "name_wanipaddr"></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wanipaddr"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Subnet Mask");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wannetmask"></span>
    </div>
    <div class="textinput">
        <span class="name" id= "name_wangateway"></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wangateway"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Primary DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wanDNSserver"></span>
    </div>
    <div class="textinput" >
        <span class="name"><?echo i18n("Secondary DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wanDNSserver2"></span>
    </div>    
    <div class="gap"></div>
</div>
<div class="blackbox" id="wan_ethernet_dslite_block" style="display:none">
    <h2><?echo i18n("WAN");?></h2>
    <div class="textinput">
        <span class="name"><?echo i18n("Connection Type");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_wantype"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Cable Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_wancable"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Network Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_networkstatus"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Connection Up Time");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_connection_uptime"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("MAC Address");?></span>
	    <span class="delimiter">:</span>
	    <span class="value" id="st_dslite_wan_mac"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("AFTR Address");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_aftrserver"></span>
    </div>
    <div class="textinput" >
        <span class="name"><?echo i18n("DS-Lite DHCPv6 option");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_dhcp6opt"></span>
    </div>    
    <div class="gap"></div>
</div>
<?
	if (isfile("/htdocs/webinc/body/st_device_3G.php")==1)
		dophp("load", "/htdocs/webinc/body/st_device_3G.php");
?>
<div class="blackbox" id="lan_ethernet_block" style="display:none">
	<h2><?echo i18n("LAN");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("MAC Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/devdata/lanmac");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("IP Address");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_ip_address"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Subnet Mask");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_netmask"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("DHCP Server");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_dhcpserver_enable"></span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox" id="ethernet_block" style="display:none">
    <h2><?echo i18n("ETHERNET");?></h2>
    <div class="textinput">
        <span class="name"><?echo i18n("Connection Type");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_wantype"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("MAC Address");?></span>
	    <span class="delimiter">:</span>
	    <span class="value"><?echo query("/runtime/devdata/lanmac");?></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("IP Address");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_ipaddr"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Subnet Mask");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_netmask"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Default Gateway");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_gateway"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Primary DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_dns1"></span>
    </div>
    <div class="textinput" >
        <span class="name"><?echo i18n("Secondary DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_dns2"></span>
    </div>    
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("WIRELESS LAN");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Radio");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_wireless_radio"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("MAC Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/devdata/wanmac");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("802.11 Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_80211mode"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel Width");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_Channel_Width"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_Channel"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_SSID"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wi-Fi Protected Setup");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_WPS_status"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Security");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_security"></span>
	</div>
	<div class="gap"></div>
</div>	
<div class="blackbox">
	<h2><?echo i18n("WIRELESS LAN2");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Radio");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_wireless_radio_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("MAC Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/devdata/wlanmac");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("802.11 Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_80211mode_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel Width");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_Channel_Width_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_Channel_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_SSID_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wi-Fi Protected Setup");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_WPS_status_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Security");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_security_Aband"></span>
	</div>
	<div class="gap"></div>
</div>	
</form>
