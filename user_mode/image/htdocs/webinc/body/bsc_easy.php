<?
function wiz_buttons()
{
	echo '<div class="emptyline"></div>\n'.
		 '	<div class="centerline">\n'.
		 '		<input type="button" name="b_pre" value="'.i18n("Prev").'" onClick="PAGE.OnClickPre();" />&nbsp;&nbsp;\n'.
		 '		<input type="button" name="b_next" value="'.i18n("Next").'" onClick="PAGE.OnClickNext();" />&nbsp;&nbsp;\n'.
		 '		<input type="button" name="b_exit" value="'.i18n("Cancel").'" onClick="PAGE.OnClickCancel();" />&nbsp;&nbsp;\n'.
		 '		<input type="button" name="b_send" value="'.i18n("Save").'" onClick="BODY.OnSubmit();" />&nbsp;&nbsp;\n'.
		 '	</div>\n'.
		 '	<div class="emptyline"></div>';
}
?>
<form id="mainform" onsubmit="return false;">
<!-- Start of Stage Description -->
<div id="easy_main" class="blackbox" style="display:none;">
	<h2><?echo i18n("Welcome to the D-Link Easy Setup");?></h2>
	<div><p class="strong">
		<?echo i18n("Use easy setup will guide you through a step-by-step process to configure your new D-Link router.");?>
	</p></div>
	<div class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">
			<input type="radio" id="en_easy" name="easy" checked="checked" />
			<?echo i18n("Easy Setup");?>
		</span>
	</div>
	<div class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">
			<input type="radio" id="en_manual" name="easy" />
			<?echo i18n("Manual Setup");?>
		</span>
	</div>
	<div><p class="strong">
		<input id="show_easy" type="checkbox" onclick="PAGE.OnClickShowEasy();"/>&nbsp;<?echo i18n("Always start up with easy setup after login.");?>
	</p></div>
	<?wiz_buttons();?>
</div>
<!-- End of Stage Description -->
<!-- Start of WAN Connection Stage -->
<div id="easy_wan" class="blackbox" style="display:none;">
	<h2><?echo i18n("Configure your Internet Connection");?></h2>
	<div class="gap"></div>
	<div class="textinput">
		<span class="name"><?echo i18n("Internet Connection");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wan_mode" onChange="PAGE.OnChangeWanType(this.value);">
				<option value="STATIC"><?echo i18n("Static IP");?></option>
				<option value="DHCP"><?echo i18n("Dynamic IP (DHCP)");?></option>
				<option value="PPPoE"><?echo i18n("PPPoE");?></option>
				<option value="PPTP"><?echo i18n("PPTP");?></option>
				<option value="L2TP"><?echo i18n("L2TP");?></option>
			</select>
		</span>
	</div>
	<!-- Start of WAN Configuration -->
	<div id="stage_wan_cfg">
		<input id="ipv4_mtu" type="hidden" />
		<input id="ppp4_mtu" type="hidden" />
		<input id="ppp4_mru" type="hidden" />
		<input id="ppp4_timeout" type="hidden" />
		<input id="ppp4_mode" type="hidden" />
		<!-- Start of DHCP -->
		<div id="DHCP"></div>
		<!-- End of DHCP -->
		<!-- Start of PPPoE -->
		<div id="PPPoE">
			<div class="textinput">
				<span class="name"><?echo i18n("User Name");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pppoe_usr" type="text" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Password");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pppoe_passwd" type="password" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Verify Password");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pppoe_passwd2" type="password" size="20" maxlength="63" />
				</span>
			</div>
		</div>
		<!-- End of PPPoE -->
		<!-- Start of PPTP -->
		<div id="PPTP">
			<div class="textinput">
				<span class="name"><?echo i18n("PPTP Server IP Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pptp_svr" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("User Name");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pptp_usr" type="text" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Password");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pptp_passwd" type="password" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Verify Password");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pptp_passwd2" type="password" size="20" maxlength="63" />
				</span>
			</div>
		</div>
		<!-- End of PPTP -->
		<!-- Start of L2TP -->
		<div id="L2TP">
			<div class="textinput">
				<span class="name"><?echo i18n("L2TP Server IP Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_l2tp_svr" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("User Name");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_l2tp_usr" type="text" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Password");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_l2tp_passwd" type="password" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Verify Password");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_l2tp_passwd2" type="password" size="20" maxlength="63" />
				</span>
			</div>
		</div>
		<!-- End of L2TP -->
		<!-- Start of STATIC -->
		<div id="STATIC">
			<div class="textinput">
				<span class="name"><?echo i18n("IP Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_ipaddr" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Subnet Mask");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_mask" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Gateway Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_gw" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Primary DNS Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_dns1" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Secondary DNS Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_dns2" type="text" size="20" maxlength="15" />
				</span>
			</div>
		</div>
		<!-- End of STATIC -->
	</div>
	<!-- End of WAN Configuration -->
	<?wiz_buttons();?>
</div>
<!-- End of WAN Connection Stage -->
<!-- Start of WLAN Configure Stage -->
<div id="easy_wlan" class="blackbox" style="display:none;">
	<h2><?echo i18n("Configure your Wireless Network");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Band");?></span>
		<span class="delimiter">:</span>
		<span class="value"><b><?echo i18n("2.4GHz Band");?></b></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="ssid" type="text" size="20" maxlength="32" />
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Security Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="security_type" onChange="PAGE.OnChangeSecurityType('');">
				<option value="none"><?echo i18n("Disable Wireless Security (not recommended)");?></option>
				<option value="wpa"><?echo i18n("Enable WPA/WPA2 Wireless Security (enhanced)");?></option>
			</select>
		</span>
	</div>
	<div id="wpa" class="textinput">
		<span class="name"><?echo i18n("Network Key");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="wpapsk" type="text" size="40" maxlength="64" />
		</span>
	</div>
	<div class="gap"></div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Band");?></span>
		<span class="delimiter">:</span>
		<span class="value"><b><?echo i18n("5GHz Band");?></b></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="ssid_Aband" type="text" size="20" maxlength="32" />
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Security Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="security_type_Aband" onChange="PAGE.OnChangeSecurityType('_Aband');">
				<option value="none"><?echo i18n("Disable Wireless Security (not recommended)");?></option>
				<option value="wpa"><?echo i18n("Enable WPA/WPA2 Wireless Security (enhanced)");?></option>
			</select>
		</span>
	</div>
	<div id="wpa_Aband" class="textinput">
		<span class="name"><?echo i18n("Network Key");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="wpapsk_Aband" type="text" size="40" maxlength="64" />
		</span>
	</div>
	<?wiz_buttons();?>
</div>
<!-- End of WLAN Configure Stage -->
<!-- Start of Summary Stage -->
<div id="easy_summary" class="blackbox" style="display:none;">
	<div id="current_setting" style="display:none"><h2><?echo i18n("Current Settings");?></h2></div>
	<div id="setup_complete">
		<h2><?echo i18n("Easy Setup Complete");?></h2>
		<p class="strong">
		<?echo i18n("The Easy Setup has completed.")." ".
				i18n("Click the Save button to save your settings and take effect.");?>
		</p>
	</div>
	<fieldset>
		<legend><?echo i18n("Internet Settings");?></legend>
		<div class="gap"></div>
		<div class="textinput">
			<span class="name"><?echo i18n("Internet Connection");?></span>
			<span class="delimiter">:</span>
			<span id="sum_wanmode" class="value"></span>
		</div>
		<div class="gap"></div>
	</fieldset>
	<fieldset>
		<legend><?echo i18n("Wireless Settings - 2.4GHz Band");?></legend>
		<div class="gap"></div>
		<div class="textinput">
			<span class="name"><?echo i18n("Network Name (SSID)");?></span>
			<span class="delimiter">:</span>
			<span id="sum_ssid" class="value"></span>
		</div>
		<div class="gap"></div>
		<div class="textinput">
			<span class="name"><?echo i18n("Security");?></span>
			<span class="delimiter">:</span>
			<span id="sum_sec" class="value"></span>
		</div>
		<div class="gap"></div>
		<div id="s_key" class="textinput">
			<span class="name"><?echo i18n("Network Key");?></span>
			<span class="delimiter">:</span>
			<span id="sum_key" class="value"></span>
		</div>
		<div id="l_key" class="centerline" style="display:none;"></div>
		<div class="gap"></div>
	</fieldset>
	<fieldset>
		<legend><?echo i18n("Wireless Settings - 5GHz Band");?></legend>
		<div class="gap"></div>
		<div class="textinput">
			<span class="name"><?echo i18n("Network Name (SSID)");?></span>
			<span class="delimiter">:</span>
			<span id="sum_ssid_Aband" class="value"></span>
		</div>
		<div class="gap"></div>
		<div class="textinput">
			<span class="name"><?echo i18n("Security");?></span>
			<span class="delimiter">:</span>
			<span id="sum_sec_Aband" class="value"></span>
		</div>
		<div class="gap"></div>
		<div id="s_key_Aband" class="textinput">
			<span class="name"><?echo i18n("Network Key");?></span>
			<span class="delimiter">:</span>
			<span id="sum_key_Aband" class="value"></span>
		</div>
		<div id="l_key_Aband" class="centerline" style="display:none;"></div>
		<div class="gap"></div>
	</fieldset>
	<div id="wiz_button_list">
		<?wiz_buttons();?>
	</div>
	<div id="setup_button_list" style="display:none">
		<div class="emptyline"></div>
		<div class="centerline">
			<?echo i18n("Reconfig via");?>&nbsp;
			<input type="button" name="b_wizard" value="<?echo i18n("Easy Setup");?>" onClick="PAGE.OnClickEasySetup();" />&nbsp;<?echo i18n("or");?>&nbsp;
			<input type="button" name="b_manaul" value="<?echo i18n("Manual Setup");?>" onClick="self.location.href='./bsc_internet.php'" />&nbsp;&nbsp;
		</div>
		<div class="emptyline"></div>
	</div>
</div>
<!-- End of Summary Stage -->
</form>
