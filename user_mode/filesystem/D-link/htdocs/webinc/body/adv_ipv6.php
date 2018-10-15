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
			<option value="STATIC"><?echo i18n("Static IPv6");?></option>
			<!-- <option value="DHCP"><?echo i18n("DHCPv6(Stateless/Stateful)");?></option> -->
			<!-- <option value="RA"><?echo i18n("Stateless Autoconfiguration");?></option> -->
			<!-- <option value="PPPOE"><?echo i18n("PPPoE");?></option> -->
			<!-- <option value="6IN4TUN"><?echo i18n("IPv6 in IPv4 Tunnel");?></option> -->
			<option value="6TO4"><?echo i18n("6to4");?></option>
			</select>
		</span>
	</div>
	<div class="gap"></div>
</div>
<!-- IPV6 CONNECTION TYPE END -->
<!-- WAN block START-->
<div class="blackbox">
	<div id="box_wan_static" style="display:none">
		<h2><?echo i18n("WAN IPv6 ADDRESS SETTINGS");?></h2>
		<p class="strong"><?echo i18n(" Enter the IPv6 address information provided by your Internet Service Provider (ISP).");?> </p>
	</div>
	<div id="box_wan_6to4" style="display:none">
		<h2><?echo i18n("6to4 SETTINGS");?></h2>
		<p class="strong"><?echo i18n(" Enter the IPv6 address information provided by your Internet Service Provider (ISP).");?> </p>
	</div>

	<div id="box_wan_static_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_ipaddr" type="text" size="42" maxlength="39" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Subnet Prefix Length");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_pl" type="text" size="4" maxlength="3" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Default Gateway");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_gw" type="text" size="42" maxlength="39" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_pdns" type="text" size="42" maxlength="39" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_st_sdns" type="text" size="42" maxlength="39" /></span>
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
			<span class="name"><?echo i18n("Primary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_6to4_pdns" type="text" size="42" maxlength="39" /></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Secondary DNS Server");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="w_6to4_sdns" type="text" size="42" maxlength="39" /></span>
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
	<div class="gap"></div>
</div>
<!-- WAN block END -->
<!-- LAN block START -->
<div class="blackbox">
	<div id="box_lan" style="display:none"> 
		<h2><?echo i18n("LAN IPv6 ADDRESS SETTINGS");?></h2>
			<p>
				<?echo i18n("Use the section to configure the internal network settings of your router.");?>
				<?echo i18n("The LAN IPv6 Link-Local Address is the IPv6 Address that you use to access the Web-based management interface.");?>
				<?echo i18n("If you change the LAN IPv6 Address here, you may need to adjust your PC's network settings to access the network again.");?>
			</p>
	</div>
	<div id="box_lan_static_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("LAN IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<span><input id="l_st_ipaddr" type="text" size="42" maxlength="39" /></span>
				<span id="l_st_pl"></span>
			</span>
		</div>
	</div>
	<div id="box_lan_6to4_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("LAN IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<span id="l_6to4_ipaddr"></span>
				<span id="l_6to4_pl"></span>
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
<div class="blackbox">
	<div id="box_lan_auto" style="display:none">
		<h2><?echo i18n("LAN ADDRESS AUTOCONFIGURATION SETTINGS");?></h2>
			<p>
				<?echo i18n("Use this section to setup IPv6 Autoconfiguration to assign IP addresses to the computers on your network.");?>
			</p>
	</div>
	<div id="box_lan_auto_body" style="display:none">
		<div class="textinput">
			<span class="name"><?echo i18n("Enable Autoconfiguration");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input id="enableAuto" value="" type="checkbox"/></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Autoconfiguration Type");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<select id="lan_auto_type" onchange="PAGE.OnChangelan_auto_type();">
				<option value="0"><?echo i18n("Stateless");?></option>
				<!--
				<option value="1"><?echo i18n("Stateful(DHCPv6)");?></option>
				-->
				</select>
			</span>
		</div>
	</div>
	<div class="gap"></div>
</div>
<!-- LAN IPV6 ADDRESS SETTING END -->
<div class="gap"></div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
