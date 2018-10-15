<?include "/htdocs/webinc/body/draw_elements.php";?>
<form id="mainform" onsubmit="return false;">
<!-- IPV6 orangebox START-->
<div class="orangebox"> 
	<h1><?echo i18n("IPv6");?></h1>
		<p>
			<?echo i18n("Use this section to configure your IPv6 Connection Type.");?>
			<?echo i18n("If you are unsure of your connection method, please contact your Internet Service Provider.");?>
		</p>
		<p>
			<input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
			<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" />
		</p>
</div>
<!-- IPV6 orangebox END-->
<!-- IPV6 CONNECTION TYPE START -->
<div class="blackbox">
	<h2><?echo i18n("IPv6 Connection Type");?></h2>
		<p class="strong">
			<?echo i18n("Choose the mode to be used by the router to connect to the IPv6 Internet.");?>
		</p>
	<div class="textinput">
		<span class="name"><?echo i18n("My IPv6 Connection is ");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wan_ipv6_mode" onchange="PAGE.OnChangewan_ipv6_mode();">
			<option value="AUTODETECT"><?echo i18n("Auto Detection");?></option>
			<option value="STATIC"><?echo i18n("Static IPv6");?></option>
			<option value="AUTO"><?echo i18n("Autoconfiguration(SLAAC/DHCPv6)");?></option>
			<!--<option value="DHCP"><?echo i18n("DHCPv6(Stateful)");?></option>-->
			<!--<option value="RA"><?echo i18n("Stateless Autoconfiguration");?></option>-->
			<option value="PPPOE"><?echo i18n("PPPoE");?></option>
			<option value="6IN4"><?echo i18n("IPv6 in IPv4 Tunnel");?></option>
			<option value="6TO4"><?echo i18n("6to4");?></option>
			<option value="6RD"><?echo i18n("6rd");?></option>
			<option value="LL"><?echo i18n("Link-local Only");?></option>
			</select>
		</span>
	</div>
	<div class="gap"></div>
</div>
<!-- IPV6 CONNECTION TYPE END -->
<!-- WAN block START-->
<div class="blackbox" id="bbox_wan" style="display:none">
	<div id="box_wan_title" style="display:none">
		<h2><?echo i18n("WAN IPv6 ADDRESS SETTINGS");?></h2>
		<p class="strong"><?echo i18n(" Enter the IPv6 address information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<div id="box_wan_static_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("Use Link-Local Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="usell" value="" type="checkbox" onClick="PAGE.OnClickUsell();"/></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_ipaddr" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Subnet Prefix Length");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_pl" type="text" size="4" maxlength="3" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Default Gateway");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_gw" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_pdns" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_sdns" type="text" size="42" maxlength="45" /></span>
		</div>
	</div>
	<div id="box_wan_pppoe" style="display:none">
		<h2><?echo i18n("PPPoE Internet Connection Type :");?></h2>
		<p class="strong"><?echo i18n("Enter the information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<div id="box_wan_pppoe_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("PPPoE Session");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input type="radio" id="pppoe_sess_share" name="pppoe_sess_type" onclick="PAGE.OnClickPppoeSessType();"/><?echo i18n("Share with IPv4");?>
				<input type="radio" id="pppoe_sess_new"  name="pppoe_sess_type" onclick="PAGE.OnClickPppoeSessType();"/><?echo i18n("Create a new session");?>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Address Mode");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input type="radio" id="pppoe_dynamic" name="pppoe_addr_type" onclick="PAGE.OnClickPppoeAddrType();"/><?echo i18n("Dynamic IP");?>
				<input type="radio" id="pppoe_static"  name="pppoe_addr_type" onclick="PAGE.OnClickPppoeAddrType();"/><?echo i18n("Static IP");?>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("IP Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pppoe_ipaddr" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Username");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="pppoe_username" type="text" size="20" maxlength="63" />
				<span id="show_pppoe_mppe" style="display:none" >&nbsp;<?echo i18n("MPPE :");?>
					<input type="checkbox" id="pppoe_mppe" name="ppp_mppe" >
				</span>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Password");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pppoe_password" type="password" size="20" maxlength="63" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Verify Password");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="confirm_pppoe_password" type="password" size="20" maxlength="63" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Service Name");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pppoe_service_name" type="text" size="30" maxlength="39" /> (<?echo i18n("optional");?>)</span>
		</div>
<!--
		<div class="textinput">
			<span class="name"><?echo i18n("Reconnect Mode");?></span>
			<span class="delimiter">:</span>
			<span class="name"></span>
			<span class="delimiter"></span>
			<span class="value">
				<input type="radio" id="pppoe_alwayson"	name="pppoe_reconnect_radio" onclick="PAGE.OnClickPppoeReconnect();"/><?echo i18n("Always on");?>
				<input type="radio" id="pppoe_ondemand"	name="pppoe_reconnect_radio" onclick="PAGE.OnClickPppoeReconnect();"/><?echo i18n("On demand");?>
			    <input type="radio" id="pppoe_manual"	name="pppoe_reconnect_radio" onclick="PAGE.OnClickPppoeReconnect();"/><?echo i18n("Manual");?>
			</span>
		</div>
-->
<!--
		<div class="textinput">
			<span class="name"><?echo i18n("Maximum Idle Time");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pppoe_max_idle_time" type="text" size="10" maxlength="5" /><?echo i18n("(minutes, 0=infinite)");?></span>
		</div>
-->
		<div class="textinput">
			<span class="name"><?echo i18n("MTU");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="ppp6_mtu" type="text" size="10" maxlength="4" /><?echo i18n("(bytes) MTU default = 1492");?></span>
		</div>
	</div>
	<div id="box_wan_6to4_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("6to4 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<span id="w_6to4_ipaddr"></span>
				<span id="w_6to4_pl"></span>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("6to4 Relay");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_6to4_relay" type="text" size="42" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_6to4_pdns" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_6to4_sdns" type="text" size="42" maxlength="45" /></span>
		</div>
	</div>
	<div id="box_wan_6rd_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("6rd Configuration");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input type="radio" id="6rd_dhcp_option" name="6rd_dhcp_option_rad" onclick="PAGE.OnClick6rdDHCPOPT();"/><?echo i18n("6rd DHCPv4 option");?>
				<input type="radio" id="6rd_manual"  name="6rd_dhcp_option_rad" onclick="PAGE.OnClick6rdDHCPOPT();"/><?echo i18n("Manual Configuration");?>
			</span>
			<span class="name"><?echo i18n("6rd IPv6 Prefix");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="w_6rd_prefix_1" type="text" size="20" maxlength="39" />	/
				<input id="w_6rd_prefix_2" type="text" size="4" maxlength="3" />
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("IPv4 Address");?></span>
			<span class="delimiter">:</span>
			<!--<span  ><span id="w_6rd_v4addr"></span></span>-->
			<span>
				<input id="w_6rd_v4addr" type="text" size="15" maxlength="15" />
			</span>
			<!--<span style="font-weight:bold;position:absolute;margin-left: 120px;"><?echo i18n("Mask Length");?>-->
			<span style="font-weight:bold;"><?echo i18n("Mask Length");?>
			:
			<!--<span class="delimiter">:</span>-->
			<input id="w_6rd_v4addr_mask" type="text" size="3" maxlength="2" /></span>	
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Assigned IPv6 Prefix");?></span>
			<span class="delimiter">:</span>
			<span class="value"><span id="w_6rd_prefix_3"></span></span>
		</div>
<!--
		<div class="textinput">
			<span class="name"><?echo i18n("Tunnel Link-Local Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><span id="w_6rd_ll_addr"></span></span>
		</div>
-->
<!--
		<div class="textinput">
			<span class="name"><?echo i18n("6rd Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><span id="w_6rd_addr"></span></span>
		</div>
-->
		<div class="textinput">
			<span class="name"><?echo i18n("6rd Border Relay IPv4 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_6rd_relay" type="text" size="15" maxlength="15" /></span></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_6rd_pdns" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_6rd_sdns" type="text" size="42" maxlength="45" /></span>
		</div>
	</div>
	<div id="box_wan_ll_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("IPv6 Link-Local Address");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<span id="wan_ll"></span>
				<span id="wan_ll_pl"></span>
			</span>
		</div>
	</div>
	<div id="box_wan_tunnel" style="display:none">
		<h2><?echo i18n("IPv6 in IPv4 TUNNEL SETTINGS");?></h2>
		<p class="strong"><?echo i18n(" Enter the IPv6 in IPv4 Tunnel information provided by your Tunnel Broker.");?> </p>
	</div>
	<div id="box_wan_tunnel_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("Remote IPv4 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_tu_rev4_ipaddr" type="text" size="21" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Remote IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_tu_rev6_ipaddr" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Local IPv4 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><span id="w_tu_lov4_ipaddr"></span></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Local IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_tu_lov6_ipaddr" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Subnet Prefix Length");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_tu_pl" type="text" size="4" maxlength="3" /></span>
		</div>
	</div>
	<div class="gap"></div>
</div>
<!-- WAN block END -->
<!-- DNS START -->
<div class="blackbox" id="bbox_wan_dns" style="display:none">
	<div id="box_wan_dns">
		<h2><?echo i18n("IPv6 DNS SETTINGS");?></h2>
		<p class="strong"><?echo i18n("Obtain DNS server address automatically or enter a specific DNS server address.");?> </p>
	</div>
	<div id="box_wan_dns_body">
		<div class="textinput">
			<span class="name"><input type="radio" id="w_dhcp_dns_auto" name="w_dhcp_dns_rad" value="auto" onclick="PAGE.OnClickDHCPDNS();" /></span>
			<span class="value"><strong><?echo i18n("Obtain IPv6 DNS Servers automatically");?></strong></span>
		</div>
		<div class="textinput">
			<span class="name"><input type="radio" id="w_dhcp_dns_manual" name="w_dhcp_dns_rad" value="manual" onclick="PAGE.OnClickDHCPDNS();" /></span>
			<span class="value"><strong><?echo i18n("Use the following IPv6 DNS Servers");?></strong></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_dhcp_pdns" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_dhcp_sdns" type="text" size="42" maxlength="45" /></span>
		</div>
	</div>
	<div class="gap"></div>
</div>
<!-- DNS END -->
<!-- LAN block START -->
<div class="blackbox">
	<div id="box_lan" style="display:none"> 
		<h2><?echo i18n("LAN IPv6 ADDRESS SETTINGS");?></h2>
			<p>
				<?echo i18n("Use this section to configure the internal network settings of your router.");?>
<!--
				<?echo i18n("The LAN IPv6 Link-Local Address is the IPv6 Address that you use to access the Web-based management interface.");?>
-->
				<span id="span_dsc1" style="display:none"><?echo i18n("If you change the LAN IPv6 Address here, you may need to adjust your PC network settings to access the network again.");?></span>
<!--
				<span id="span_dsc2" style="display:none"><?echo i18n("DHCP-PD can be used to acquire a IPv6 prefix for the LAN interface.");?></span>
-->
			</p>
	</div>
	<div id="box_lan_pd_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("Enable DHCP-PD");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="en_dhcp_pd" type="checkbox" onclick="PAGE.OnClickpd();"/></span>
		</div>
	</div>
	<div id="box_lan_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("LAN IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<span><input id="l_ipaddr" type="text" size="42" maxlength="45" /></span>
				<span id="l_pl"></span>
			</span>
		</div>
	</div>
	<div id="box_lan_ll_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("LAN IPv6 Link-Local Address");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<span id="lan_ll"></span>	
				<span id="lan_ll_pl"></span>
			</span>
		</div>
	</div>
	<div class="gap"></div>
</div>
<!-- LAN block END -->
<!-- LAN IPV6 ADDRESS SETTING START -->
<div class="blackbox" id="bbox_lan_auto" stle="display:none">
	<div id="box_lan_auto" style="display:none">
		<h2><?echo i18n("ADDRESS AUTOCONFIGURATION SETTINGS");?></h2>
			<p>
				<?echo i18n("Use this section to setup IPv6 Autoconfiguration to assign IP addresses to the computers on your network.");?>
			</p>
	</div>
	<div id="box_lan_auto_pd" style="display:none">
		<h2><?echo i18n("ADDRESS AUTOCONFIGURATION SETTINGS");?></h2>
			<p>
				<?echo i18n("Use this section to setup IPv6 Autoconfiguration to assign IP addresses to the computers on your network. You can also enable DHCP-PD to delegate prefixes for routers in your LAN.");?>
			</p>
	</div>
	<div id="box_lan_auto_body" style="display:none">  
		<div class="textinput">
			<span class="name"><?echo i18n("Enable Automatic IPv6 address assignment");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="enableAuto" value="" type="checkbox" onClick="PAGE.OnClickAuto();"/></span>
		</div>
		<div id="box_lan_auto_pd_body" style="display:none">
			<div class="textinput">
				<span class="name"><?echo i18n("Enable Automatic DHCP-PD in LAN");?></span>
				<span class="delimiter">:</span>
				<span class="value"><input id="en_lan_pd" value="" type="checkbox" onClick="PAGE.OnClickLanpd();"/></span>
			</div>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Autoconfiguration Type");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<select id="lan_auto_type" onchange="PAGE.OnChangelan_auto_type();">
				<option value="STATELESSR"><?echo i18n("SLAAC+RDNSS");?></option>
				<option value="STATELESSD"><?echo i18n("SLAAC+Stateless DHCP");?></option>
				<option value="STATEFUL"><?echo i18n("Stateful DHCPv6");?></option>
				</select>
			</span>
		</div>
	</div>
         <div id="box_lan_stless" style="display:none">
                        <div class="textinput">
                                <span class="name"><?echo i18n("Router Advertisement Lifetime");?></span>
                                <span class="delimiter">:</span>
                                <span class="value"><input id="ra_lifetime" type="text" size="5" maxlength="3" />
                                (<?echo i18n("minutes");?>)</span>
                        </div>
         </div>
         <div id="box_lan_dhcp" style="display:none">
                <div class="textinput">
			        <span class="name"><?echo i18n("IPv6 Address Range (Start)");?></span>
			        <span class="delimiter">:</span>
			        <span class="value"><input id="dhcps_start_ip_prefix" type="text" size="30" maxlength="39" />
			        <span id="sp_dli_s"></span>
			        <input id="dhcps_start_ip_value" type="text" size="3" maxlength="2" />
			        <span id="l_range_start_pl"></span>
			        </span>	
		        </div>
		        <div class="textinput">
			        <span class="name"><?echo i18n("IPv6 Address Range (End)");?></span>
			        <span class="delimiter">:</span>
			        <span class="value"><input id="dhcps_stop_ip_prefix" type="text" size="30" maxlength="39" />
			        <span id="sp_dli_e"></span>
			        <input id="dhcps_stop_ip_value" type="text" size="3" maxlength="2" />
			        <span id="l_range_end_pl"></span>
			        </span>	
		        </div>
                <div class="textinput">
                        <span class="name"><?echo i18n("IPv6 Address Lifetime");?></span>
                        <span class="delimiter">:</span>
                        <span class="value"><input id="ip_lifetime" type="text" size="5" maxlength="3" />
                        (<?echo i18n("minutes");?>)</span>
                </div>
	</div>
	<div class="gap"></div>
</div>
<!-- LAN IPV6 ADDRESS SETTING END -->
<div class="gap"></div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
