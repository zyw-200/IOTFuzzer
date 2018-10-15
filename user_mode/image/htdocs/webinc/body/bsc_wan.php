<?include "/htdocs/webinc/body/draw_elements.php";?>
<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("WAN");?></h1>
		<p>
			<?echo i18n("Use this section to configure your Internet Connection type.");?>
			<?echo i18n("There are several connection types to choose from: Static IP, DHCP, PPPoE, PPTP, and L2TP.");?>
			<?echo i18n("If you are unsure of your connection method, please contact your Internet Service Provider.");?>
		</p>
		<p>
			<strong><?echo i18n("Note :");?></strong>
			<?echo i18n("If using the PPPoE option, you will need to remove or disable any PPPoE client software on your computers.");?>
		</p>
		<p>
			<input type="button" id="topsave" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
			<input type="button" id="topcancel" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" />
		</p>
</div>
<!-- router/bridge mdoe -->
<div class="blackbox" <?if ($FEATURE_NOAPMODE == "1") echo 'style="display:none;"';?> >
	<h2><?echo i18n("Access Point Mode");?></h2>
		<p class="strong">
			<?echo i18n("Use this to disable NAT on the router and turn it into an Access Point.");?>
		</p>
		<p><input type="checkbox" id="rgmode" onclick="PAGE.OnClickRgmode('checkbox');" /><?echo i18n("Enabled Access Point Mode");?></p>
	<div class="gap"></div>
</div>
<!-- wan mode -->
<div class="blackbox">
	<h2><?echo i18n("Internet Connection Type");?></h2>
		<p class="strong">
			<?echo i18n("Choose the mode to be used by the router to connect to the Internet.");?>
		</p>
	<div class="textinput">
		<span class="name"><?echo i18n("My Internet Connection is ");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wan_ip_mode" onchange="PAGE.OnChangeWanIpMode();">
				<option value="static"><?echo i18n("Static IP");?></option>
				<option value="dhcp"><?echo i18n("Dynamic IP (DHCP)");?></option>																 
				<?if ($FEATURE_DHCPPLUS=="1")		echo '<option value="dhcpplus">'.i18n("DHCP Plus (Username / Password)").'</option>\n';?>
				<option value="pppoe"><?echo i18n("PPPoE  (Username / Password)");?></option>
				<?if ($FEATURE_NOPPTP!="1")			echo '<option value="pptp">'	.i18n("PPTP (Username / Password)").'</option>\n';?>
				<?if ($FEATURE_NOL2TP!="1")			echo '<option value="l2tp">'	.i18n("L2TP (Username / Password)").'</option>\n';?>
				<?if ($FEATURE_NORUSSIAPPTP!="1")	echo '<option value="r_pptp">'	.i18n("Russia PPTP (Dual Access)").'</option>\n';?>
				<?if ($FEATURE_NORUSSIAPPPOE!="1")	echo '<option value="r_pppoe">'	.i18n("Russia PPPoE (Dual Access)").'</option>\n';?>
				<?if ($FEATURE_NODSLITE!="1")	echo '<option value="dslite">'	.i18n("DS-Lite").'</option>\n';?>
			</select>
		</span>
	</div>
	<div class="gap"></div>
</div>

<!-- ipv4 settings: static & dhcp -->
<div class="blackbox" id="ipv4_setting">
	<!-- header -->
	<div id="box_wan_static" style="display:none">
		<h2><?echo i18n("Static IP Address Internet Connection Type :");?></h2>
		<p class="strong">
			<?echo i18n(" Enter the static address information provided by your Internet Service Provider (ISP).");?>
		</p>
	</div>
	<div id="box_wan_dhcp" style="display:none">
		<h2><?echo i18n("Dynamic IP (DHCP) Internet Connection Type :");?></h2>
		<p class="strong">
			<?echo i18n("Use this Internet connection type if your Internet Service Provider (ISP) didn't provide you with IP Address information and/or a username and password.");?>
		</p>
	</div>
	<div id="box_wan_dhcpplus" style="display:none">
		<h2><?echo i18n("DHCP Plus Internet Connection Type :");?></h2>
	</div>
	<!-- end of header -->
	<!-- static -->
	<div id="box_wan_static_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("IP Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="st_ipaddr" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Subnet Mask");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="st_mask" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Default Gateway");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="st_gw" type="text" size="20" maxlength="15" /></span>
		</div>
	</div>
	<!-- dhcp -->
	<div id="box_wan_dhcp_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("Host Name");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="dhcp_host_name" type="text" size="25" maxlength="39" /></span>
		</div>
		<div id="dhcpplus" style="display:none">
			<div class="textinput">
				<span class="name"><?echo i18n("Username");?></span>
				<span class="delimiter">:</span>
				<span class="value"><input id="dhcpplus_username" type="text" size="20" maxlength="63" /></span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Password");?></span>
				<span class="delimiter">:</span>
				<span class="value"><input id="dhcpplus_password" type="password" size="20" maxlength="63" /></span>
			</div>
		</div>
	<!--<div class="textinput">
			<span class="name"><?echo i18n("Use Unicasting");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="checkbox" id="dhcp_use_unicasting_select" onclick="dhcp_use_unicasting_selector(this.checked);"/>&nbsp;&nbsp;<?echo i18n("(compatibility for some DHCP Servers)");?></span>
		</div>-->
	</div><!-- box_wan_dhcp_body -->
	<!-- common -->
	<div id="box_wan_ipv4_common_body">
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="ipv4_dns1" type="text" size="20" maxlength="15" />
				<span id="ipv4_dns1_optional" style="display:none"></span>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="ipv4_dns2" type="text" size="20" maxlength="15" />
				(<?echo i18n("optional");?>)
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("MTU");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="ipv4_mtu" type="text" size="10" maxlength="4" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("MAC Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="ipv4_macaddr" type="text" size="20" maxlength="17" /></span>
		</div>
		<div class="textinput">
			<span class="name"></span>
			<span class="delimiter"></span>
			<span class="value"><input id="ipv4_mac_button" type="button" value="<?echo i18n("Clone Your PC's MAC Address");?>" onclick="PAGE.OnClickMacButton('ipv4_macaddr');" /></span>
		</div>
	</div>
	<div class="gap"></div>
	<!-- end common -->
</div>

<!-- ppp4 -->
<div class="blackbox" id="ppp4_setting">
	<!-- header -->
	<div id="box_wan_pppoe" style="display:none">
		<h2><?echo i18n("PPPoE Internet Connection Type :");?></h2>
		<p class="strong"><?echo i18n("Enter the information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<div id="box_wan_pptp" style="display:none">
		<h2><?echo i18n("PPTP Internet Connection Type :");?></h2>
		<p class="strong"> <?echo i18n("Enter the information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<div id="box_wan_l2tp" style="display:none">
		<h2><?echo i18n("L2TP Internet Connection Type :");?></h2>
		<p class="strong"> <?echo i18n("Enter the information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<div id="box_wan_ru_pppoe" style="display:none">
		<h2><?echo i18n("Russia PPPoE (DUAL Access) :");?></h2>
		<p class="strong"><?echo i18n(" Enter the information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<div id="box_wan_ru_pptp" style="display:none">
	    <h2><?echo i18n("Russia PPTP (DUAL Access) :");?></h2>
	    <p class="strong"> <?echo i18n("Enter the information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<!-- end of header -->
	<!-- pppoe -->
	<div id="box_wan_pppoe_body" style="display:none">
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
			<span class="value"><input id="pppoe_ipaddr" type="text" size="20" maxlength="15" /></span>
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
		<div class="textinput">
			<span class="name"><?echo i18n("Reconnect Mode");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input type="radio" id="pppoe_alwayson" name="pppoe_reconnect_radio" onclick="PAGE.OnClickPppoeReconnect();"/><?

if ($FEATURE_NOSCH=="1")	echo i18n("Always on").'\n<span style="display:none">\n';

DRAW_select_sch("pppoe_schedule",i18n("Always on"),"","","","");
echo '<input type="button" id="pppoe_schedule_button" value="'.i18n("New Schedule").'" onclick=\'self.location.href="tools_sch.php";\' />\n';

if ($FEATURE_NOSCH=="1")	echo '</span>\n';

			?></span>
		</div>
		<div class="textinput">
			<span class="name"></span>
			<span class="delimiter"></span>
			<span class="value">
				<input type="radio" id="pppoe_ondemand"	name="pppoe_reconnect_radio" onclick="PAGE.OnClickPppoeReconnect();"/><?echo i18n("On demand");?>
			    <input type="radio" id="pppoe_manual"	name="pppoe_reconnect_radio" onclick="PAGE.OnClickPppoeReconnect();"/><?echo i18n("Manual");?>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Maximum Idle Time");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pppoe_max_idle_time" type="text" size="10" maxlength="5" /><?echo i18n("(minutes, 0=infinite)");?></span>
		</div>
		<div class="textinput"<?if ($FEATURE_FAKEOS!="1")echo ' style="display:none"';?>>
			<span class="name"><input type="checkbox" id="en_fakeos" /></span>
			<span class="delimiter">:</span>
			<span class="value"><?echo i18n("I want to use Netblock.");?></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("DNS Mode");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="dns_isp"		type="radio" name="dns_mode" onclick="PAGE.OnClickDnsMode();"/><?echo i18n("Receive DNS from ISP");?>
				<input id="dns_manual"	type="radio" name="dns_mode" onclick="PAGE.OnClickDnsMode();"/><?echo i18n("Enter DNS Manually ");?>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pppoe_dns1" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="pppoe_dns2" type="text" size="20" maxlength="15" />
				(<?echo i18n("optional");?>)
			</span>
		</div>
	</div>
	<!-- pptp -->
	<div id="box_wan_pptp_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("Address Mode");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="pptp_dynamic" type="radio" name="pptp_addr_type" onclick="PAGE.OnClickPptpAddrType();"/><?echo i18n("Dynamic IP");?>
				<input id="pptp_static"  type="radio" name="pptp_addr_type" onclick="PAGE.OnClickPptpAddrType();"/><?echo i18n("Static IP");?>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("PPTP IP Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pptp_ipaddr" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("PPTP Subnet Mask");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pptp_mask" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("PPTP Gateway IP Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pptp_gw" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("PPTP Server IP Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pptp_server" type="text" size="20" maxlength="32" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Username");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="pptp_username" type="text" size="20" maxlength="63" />
				<span id="show_pptp_mppe" style="display:none" >&nbsp;<?echo i18n("MPPE :");?>
					<input type="checkbox" id="pptp_mppe" name="pptp_mppe" >
				</span>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Password");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pptp_password" type="password" size="20" maxlength="63" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Verify Password");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="confirm_pptp_password" type="password" size="20" maxlength="63" /></span>
		</div>	
		<div class="textinput">
			<span class="name"><?echo i18n("Reconnect Mode");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input type="radio" id="pptp_alwayson" name="pptp_reconnect_radio" onclick="PAGE.OnClickPptpReconnect();"/><?

if ($FEATURE_NOSCH=="1")	echo i18n("Always on").'\n<span style="display:none">\n';

DRAW_select_sch("pptp_schedule",i18n("Always on"),"","","","");
echo '<input type="button" id="pptp_schedule_button" value="'.i18n("New Schedule").'" onclick=\'self.location.href="tools_sch.php";\' />\n';

if ($FEATURE_NOSCH=="1")	echo '</span>\n';

			?></span>
		</div>
		<div class="textinput">
			<span class="name"></span>
			<span class="delimiter"></span>
			<span class="value">
				<input type="radio" id="pptp_ondemand"	name="pptp_reconnect_radio" onclick="PAGE.OnClickPptpReconnect();"/><?echo i18n("On demand");?>
			    <input type="radio" id="pptp_manual"	name="pptp_reconnect_radio" onclick="PAGE.OnClickPptpReconnect();"/><?echo i18n("Manual");?>
			</span>
		</div>                                                 
		<div class="textinput">
			<span class="name"><?echo i18n("Maximum Idle Time");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pptp_max_idle_time" type="text" size="10" maxlength="5" /><?echo i18n("(minutes, 0=infinite)");?></span>
		</div>                                                                             
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="pptp_dns1" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="pptp_dns2" type="text" size="20" maxlength="15" />
				(<?echo i18n("optional");?>)
			</span>
		</div>
	</div><!-- box_wan_pptp_body -->
	<!-- l2tp -->
	<div id="box_wan_l2tp_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("Address Mode");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="l2tp_dynamic" type="radio" name="l2tp_addr_type" onclick="PAGE.OnClickL2tpAddrType();"/><?echo i18n("Dynamic IP");?>
				<input id="l2tp_static"  type="radio" name="l2tp_addr_type" onclick="PAGE.OnClickL2tpAddrType();"/><?echo i18n("Static IP");?>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("L2TP IP Address");?></span>
			<span class="delimiter">:</span>	
			<span class="value"><input id="l2tp_ipaddr" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("L2TP Subnet Mask");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="l2tp_mask" type="text" size="20" maxlength="15" /></span>
		</div>	
		<div class="textinput">
			<span class="name"><?echo i18n("L2TP Gateway IP Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="l2tp_gw" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("L2TP Server IP Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="l2tp_server" type="text" size="20" maxlength="32" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Username");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="l2tp_username" type="text" size="20" maxlength="63" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Password");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="l2tp_password" type="password" size="20" maxlength="63" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Verify Password");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="confirm_l2tp_password" type="password" size="20" maxlength="63" /></span>
		</div>	
		<div class="textinput">
			<span class="name"><?echo i18n("Reconnect Mode");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input type="radio" id="l2tp_alwayson" name="l2tp_reconnect_radio" onclick="PAGE.OnClickL2tpReconnect();"/><?

if ($FEATURE_NOSCH=="1")	echo i18n("Always on").'\n<span style="display:none">\n';

DRAW_select_sch("l2tp_schedule",i18n("Always on"),"","","","");
echo '<input type="button" id="l2tp_schedule_button" value="'.i18n("New Schedule").'" onclick=\'self.location.href="tools_sch.php";\' />\n';

if ($FEATURE_NOSCH=="1")	echo '</span>\n';

			?></span>
		</div>
		<div class="textinput">
			<span class="name"></span>
			<span class="delimiter"></span>
			<span class="value">
				<input type="radio" id="l2tp_ondemand"	name="l2tp_reconnect_radio" onclick="PAGE.OnClickL2tpReconnect();"/><?echo i18n("On demand");?>
			    <input type="radio" id="l2tp_manual"	name="l2tp_reconnect_radio" onclick="PAGE.OnClickL2tpReconnect();"/><?echo i18n("Manual");?>
			</span>
		</div>                                                 
		<div class="textinput">
			<span class="name"><?echo i18n("Maximum Idle Time");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="l2tp_max_idle_time" type="text" size="10" maxlength="5" /><?echo i18n("(minutes, 0=infinite)");?></span>
		</div>                                                                             
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="l2tp_dns1" type="text" size="20" maxlength="15" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input id="l2tp_dns2" type="text" size="20" maxlength="15" />
				(<?echo i18n("optional");?>)
			</span>
		</div>
	</div><!-- box_wan_l2tp_body -->
	<!-- common -->
	<div id="box_wan_ppp4_comm_body">
		<div class="textinput">
			<span class="name"><?echo i18n("MTU");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="ppp4_mtu" type="text" size="10" maxlength="4" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("MAC Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="ppp4_macaddr" type="text" size="20" maxlength="17" /></span>
		</div>
		<div class="textinput">
			<span class="name"></span>
			<span class="delimiter"></span>
			<span class="value">
				<input id="mac_button" type="button" value="<?echo i18n("Clone Your PC's MAC Address");?>" onclick="PAGE.OnClickMacButton('ppp4_macaddr');" />
			</span>
		</div>
	</div>
	<div class="gap"></div>
</div><!--blackbox -->

<!-- Russia PPPoE -->
<div id="R_PPPoE" class="blackbox" style="display:none">
	<h2><?echo i18n("WAN Physical Settings");?></h2>
	<div class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">
			<input id="rpppoe_dynamic" type="radio" name="rpppoe_addr_type" onclick="PAGE.OnClickRPppoeAddrType();"/><?echo i18n("Dynamic IP");?>
			<input id="rpppoe_static"  type="radio" name="rpppoe_addr_type" onclick="PAGE.OnClickRPppoeAddrType();"/><?echo i18n("Static IP");?>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("IP Address");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="rpppoe_ipaddr" type="text" size="20" maxlength="15" />
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Subnet Mask");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="rpppoe_mask" type="text" size="20" maxlength="15" />
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Gateway");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="rpppoe_gw" type="text" size="20" maxlength="15" />
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Primary DNS Address");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="rpppoe_dns1" type="text" size="20" maxlength="15" />
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Secondary DNS Address");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="rpppoe_dns2" type="text" size="20" maxlength="15" />
			(<?echo i18n("optional");?>)
		</span>
	</div>
	<div class="emptyline"></div>
</div>
<!-- DS-Lite -->
<div class="blackbox" id="dslite_setting">
	<div id="box_wan_dslite" style="display:none">
		<h2><?echo i18n("AFTR Address Internet Connection Type :");?></h2>
		<p class="strong"><?echo i18n("Enter the AFTR address information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<div id="box_wan_dslite_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("DS-Lite Configuration");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<input type="radio" id="dslite_dynamic" name="dslite_addr_type" onclick="PAGE.OnClickDsliteAddrType();"/><?echo i18n("DS-Lite DHCPv6 Option");?>
				<input type="radio" id="dslite_static"  name="dslite_addr_type" onclick="PAGE.OnClickDsliteAddrType();"/><?echo i18n("Manual Configuration");?>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("AFTR IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="aftr_ipaddr6" type="text" size="42" maxlength="45" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("B4 IPv4 Address");?></span>
			<span class="delimiter">:</span>
			<span id="b4_ipaddr_1"></span>
			<input id="b4_ipaddr_2" type="text" size="3" maxlength="3" /><?echo i18n("(optional)");?>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("WAN IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value" id="dslite_wan_ipaddr6"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("IPv6 WAN Default Gateway");?></span>
			<span class="delimiter">:</span>
			<span class="value" id="dslite_gw_ipaddr6"></span>
		</div>
		<div class="emptyline"></div>
	</div>
</div>
<p><input type="button" id="bottomsave" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" id="bottomcancel" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
