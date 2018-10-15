<?
include "/htdocs/webinc/body/draw_elements.php";
include "/htdocs/phplib/wifi.php";
?>
<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Wireless Network");?></h1>
	<p><?echo i18n("Use this section to configure the wireless settings for your D-Link router.")." ".
		i18n("Please note that changes made in this section may also need to be duplicated on your wireless client. ");?></p>
	<p><?echo i18n("To protect your privacy you can configure wireless security features.")." ".
		i18n("This device supports three wireless security modes including: WEP, WPA and WPA2.");?></p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onClick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onClick="BODY.OnReload();" /></p>
</div>

<!-- ===================== 2.4Ghz, BG band ============================== -->
<div class="blackbox">
	<h2><?echo i18n("Wireless Network Settings");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Band");?></span>
		<span class="delimiter">:</span>
		<span class="value"><b><?echo i18n("2.4GHz Band");?></b></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Wireless");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="en_wifi" type="checkbox" onClick="PAGE.OnClickEnWLAN('');" />
			<?
			if ($FEATURE_NOSCH!="1")
			{
				DRAW_select_sch("sch", i18n("Always"), "", "", "0", "narrow");
				echo '<input id="go2sch" type="button" value="'.i18n("New Schedule").'" onClick="javascript:self.location.href=\'./tools_sch.php\';" />\n';
			}
			?>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Network Name");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="ssid" type="text" size="20" maxlength="32" />
			(<?echo i18n("Also called the SSID");?>)
		</span>
	</div>
	
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Auto Channel Selection");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="auto_ch" type="checkbox" onClick="PAGE.OnClickEnAutoChannel('');" /></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="channel">
<?
	$clist = WIFI_getchannellist();
	$count = cut_count($clist, ",");

	$i = 0;
	while($i < $count)
	{
		$ch = cut($clist, $i, ',');
		echo '\t\t\t\t<option value="'.$ch.'">'.$ch.'</option>\n';
		$i++;
	}
?>			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Transmission Rate");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="txrate">
				<option value="-1"><?echo i18n("Best")." (".i18n("automatic").")";?></option>
			</select>
			(Mbit/s)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("WMM Enable");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="en_wmm" type="checkbox" />
			(<?echo i18n("Wireless QoS");?>)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Hidden Wireless");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="suppress" type="checkbox" />
			(<?echo i18n("Also called the SSID Broadcast");?>)
		</span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Wireless Security Mode");?></h2>
	<div class="textinput">
		<span class="name" <? if(query("/runtime/device/langcode")!="en") echo 'style="width: 28%;"';?> ><?echo i18n("Security Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="security_type" onChange="PAGE.OnChangeSecurityType('');">
				<option value=""><?echo i18n("Disable Wireless Security (not recommended)");?></option>
				<option value="wep"><?echo i18n("Enable WEP Wireless Security (basic)");?></option>
				<option value="WPA"><?echo i18n("Enable WPA Wireless Security (enhanced)");?></option>
				<option value="WPA2"><?echo i18n("Enable WPA2 Wireless Security (enhanced)");?></option>
				<option value="WPA+2"><?echo i18n("Enable WPA/WPA2 Wireless Security (enhanced)");?></option>


			</select>
		</span>
	</div>
	<div class="gap"></div>
</div>
<div id="wep" class="blackbox" style="display:none;">
	<h2><?echo i18n("WEP");?></h2>
	<p><?echo i18n("WEP is the wireless encryption standard.")." ".
		i18n("To use it you must enter the same key(s) into the router and the wireless stations.")." ".
		i18n("For 64-bit keys you must enter 10 hex digits into each key box.")." ".
		i18n("For 128-bit keys you must enter 26 hex digits into each key box. ")." ".
		i18n("A hex digit is either a number from 0 to 9 or a letter from A to F.")." ".
		i18n('For the most secure use of WEP set the authentication type to "Shared Key" when WEP is enabled.');?></p>
	<p><?echo i18n("You may also enter any text string into a WEP key box, in which case it will be converted into a hexadecimal key using the ASCII values of the characters.")." ".
		i18n("A maximum of 5 text characters can be entered for 64-bit keys, and a maximum of 13 characters for 128-bit keys.");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("Authentication");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="auth_type">
				<option value="OPEN">Open</option>
				<option value="SHARED">Shared Key</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("WEP Encryption");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wep_key_len" onChange="PAGE.OnChangeWEPKey('');">
				<option value="64">64Bit</option>
				<option value="128">128Bit</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Default WEP Key");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wep_def_key" onChange="PAGE.OnChangeWEPKey('');">
				<option value="1">WEP Key 1</option>
				<option value="2">WEP Key 2</option>
				<option value="3">WEP Key 3</option>
				<option value="4">WEP Key 4</option>
			</select>
		</span>
	</div>
	<div id="wep_64" class="textinput">
		<span class="name"><?echo i18n("WEP Key");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="wep_64_1" name="wepkey_64" type="text" size="15" maxlength="10" />
			<input id="wep_64_2" name="wepkey_64" type="text" size="15" maxlength="10" />
			<input id="wep_64_3" name="wepkey_64" type="text" size="15" maxlength="10" />
			<input id="wep_64_4" name="wepkey_64" type="text" size="15" maxlength="10" />
			(5 ASCII or 10 HEX)
		</span>
	</div>
	<div id="wep_128" class="textinput">
		<span class="name"><?echo i18n("WEP Key");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="wep_128_1" name="wepkey_128" type="text" size="31" maxlength="26" />
			<input id="wep_128_2" name="wepkey_128" type="text" size="31" maxlength="26" />
			<input id="wep_128_3" name="wepkey_128" type="text" size="31" maxlength="26" />
			<input id="wep_128_4" name="wepkey_128" type="text" size="31" maxlength="26" />
			(13 ASCII or 26 HEX)
		</span>
	</div>
	<div class="gap"></div>
</div>
<div id="wpa" class="blackbox" style="display:none;">
	<h2><?echo i18n("WPA/WPA2");?></h2>
	<p><?echo i18n("WPA/WPA2 requires stations to use high grade encryption and authentication.");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("Cipher Type");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="cipher_type">
				<option value="TKIP+AES">AUTO(TKIP/AES)</option>
				<option value="TKIP">TKIP</option>
				<option value="AES">AES</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("PSK / EAP");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="psk_eap" onChange="PAGE.OnChangeWPAAuth('');">
				<option value="psk">PSK</option>
				<option value="eap">EAP</option>
			</select>
		</span>
	</div>
	<div name="psk" class="textinput">
		<span class="name"><?echo i18n("Network Key");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="wpapsk" type="text" size="40" maxlength="64" /></span>
	</div>
	<div name="psk" class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">(8~63 ASCII or 64 HEX)</span>
	</div>
	<div name="eap" class="textinput">
		<span class="name"><?echo i18n("RADIUS Server IP Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="srv_ip" type="text" size="15" maxlength="15" /></span>
	</div>
	<div name="eap" class="textinput">
		<span class="name"><?echo i18n("Port");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="srv_port" type="text" size="5" maxlength="5" /></span>
	</div>
	<div name="eap" class="textinput">
		<span class="name"><?echo i18n("Shared Secret");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="srv_sec" type="password" size="50" maxlength="64" /></span>
	</div>
	<div class="gap"></div>
</div>


<!-- ===================== 5Ghz, A band ============================== -->
<div class="blackbox">
	<h2><?echo i18n("Wireless Network Settings");?></h2>

	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Band");?></span>
		<span class="delimiter">:</span>
		<span class="value"><b><?echo i18n("5GHz Band");?></b></span>
	</div>

	<div class="textinput">
		<span class="name"><?echo i18n("Enable Wireless");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="en_wifi_Aband" type="checkbox" onClick="PAGE.OnClickEnWLAN('_Aband');" />
			<?
			if ($FEATURE_NOSCH!="1")
			{
				DRAW_select_sch("sch_Aband", i18n("Always"), "", "", "0", "narrow");
				echo '<input id="go2sch_Aband" type="button" value="'.i18n("New Schedule").'" onClick="javascript:self.location.href=\'./tools_sch.php\';" />\n';
			}
			?>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Network Name");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="ssid_Aband" type="text" size="20" maxlength="32" />
			(<?echo i18n("Also called the SSID");?>)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Auto Channel Selection");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="auto_ch_Aband" type="checkbox" onClick="PAGE.OnClickEnAutoChannel('_Aband');" /></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="channel_Aband">
<?
	$clist = WIFI_getchannellist("a");
	$count = cut_count($clist, ",");
	$i = 0;
	while($i < $count)
	{
		$ch = cut($clist, $i, ',');
		//$val = scut($ch, 0, "CH");
		echo '\t\t\t\t<option value="'.$ch.'">'.$ch.'</option>\n';
		$i++;
	}
?>			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Transmission Rate");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="txrate_Aband">
				<option value="-1"><?echo i18n("Best")." (".i18n("automatic").")";?></option>
			</select>
			(Mbit/s)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("WMM Enable");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="en_wmm_Aband" type="checkbox" />
			(<?echo i18n("Wireless QoS");?>)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Hidden Wireless");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="suppress_Aband" type="checkbox" />
			(<?echo i18n("Also called the SSID Broadcast");?>)
		</span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Wireless Security Mode");?></h2>
	<div class="textinput">
		<span class="name" <? if(query("/runtime/device/langcode")!="en") echo 'style="width: 28%;"';?> ><?echo i18n("Security Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="security_type_Aband" onChange="PAGE.OnChangeSecurityType('_Aband');">
				<option value=""><?echo i18n("Disable Wireless Security (not recommended)");?></option>
				<option value="wep"><?echo i18n("Enable WEP Wireless Security (basic)");?></option>
				<option value="WPA"><?echo i18n("Enable WPA Wireless Security (enhanced)");?></option>
				<option value="WPA2"><?echo i18n("Enable WPA2 Wireless Security (enhanced)");?></option>
				<option value="WPA+2"><?echo i18n("Enable WPA/WPA2 Wireless Security (enhanced)");?></option>
			</select>
		</span>
	</div>
	<div class="gap"></div>
</div>
<div id="wep_Aband" class="blackbox" style="display:none;">
	<h2><?echo i18n("WEP");?></h2>
	<p><?echo i18n("WEP is the wireless encryption standard.")." ".
		i18n("To use it you must enter the same key(s) into the router and the wireless stations.")." ".
		i18n("For 64-bit keys you must enter 10 hex digits into each key box.")." ".
		i18n("For 128-bit keys you must enter 26 hex digits into each key box. ")." ".
		i18n("A hex digit is either a number from 0 to 9 or a letter from A to F.")." ".
		i18n('For the most secure use of WEP set the authentication type to "Shared Key" when WEP is enabled.');?></p>
	<p><?echo i18n("You may also enter any text string into a WEP key box, in which case it will be converted into a hexadecimal key using the ASCII values of the characters.")." ".
		i18n("A maximum of 5 text characters can be entered for 64-bit keys, and a maximum of 13 characters for 128-bit keys.");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("Authentication");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="auth_type_Aband">
				<option value="OPEN">Open</option>
				<option value="SHARED">Shared Key</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("WEP Encryption");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wep_key_len_Aband" onChange="PAGE.OnChangeWEPKey('_Aband');">
				<option value="64">64Bit</option>
				<option value="128">128Bit</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Default WEP Key");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wep_def_key_Aband" onChange="PAGE.OnChangeWEPKey('_Aband');">
				<option value="1">WEP Key 1</option>
				<option value="2">WEP Key 2</option>
				<option value="3">WEP Key 3</option>
				<option value="4">WEP Key 4</option>
			</select>
		</span>
	</div>
	<div id="wep_64_Aband" class="textinput">
		<span class="name"><?echo i18n("WEP Key");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="wep_64_1_Aband" name="wepkey_64_Aband" type="text" size="15" maxlength="10" />
			<input id="wep_64_2_Aband" name="wepkey_64_Aband" type="text" size="15" maxlength="10" />
			<input id="wep_64_3_Aband" name="wepkey_64_Aband" type="text" size="15" maxlength="10" />
			<input id="wep_64_4_Aband" name="wepkey_64_Aband" type="text" size="15" maxlength="10" />
			(5 ASCII or 10 HEX)
		</span>
	</div>
	<div id="wep_128_Aband" class="textinput">
		<span class="name"><?echo i18n("WEP Key");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="wep_128_1_Aband" name="wepkey_128_Aband" type="text" size="31" maxlength="26" />
			<input id="wep_128_2_Aband" name="wepkey_128_Aband" type="text" size="31" maxlength="26" />
			<input id="wep_128_3_Aband" name="wepkey_128_Aband" type="text" size="31" maxlength="26" />
			<input id="wep_128_4_Aband" name="wepkey_128_Aband" type="text" size="31" maxlength="26" />
			(13 ASCII or 26 HEX)
		</span>
	</div>
	<div class="gap"></div>
</div>
<div id="wpa_Aband" class="blackbox" style="display:none;">
	<h2><?echo i18n("WPA/WPA2");?></h2>
	<p><?echo i18n("WPA/WPA2 requires stations to use high grade encryption and authentication.");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("Cipher Type");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="cipher_type_Aband">
				<option value="TKIP+AES">AUTO(TKIP/AES)</option>
				<option value="TKIP">TKIP</option>
				<option value="AES">AES</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("PSK / EAP");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="psk_eap_Aband" onChange="PAGE.OnChangeWPAAuth('_Aband');">
				<option value="psk">PSK</option>
				<option value="eap">EAP</option>
			</select>
		</span>
	</div>
	<div name="psk_Aband" class="textinput">
		<span class="name"><?echo i18n("Network Key");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="wpapsk_Aband" type="text" size="40" maxlength="64" /></span>
	</div>
	<div name="psk_Aband" class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">(8~63 ASCII or 64 HEX)</span>
	</div>
	<div name="eap_Aband" class="textinput">
		<span class="name"><?echo i18n("RADIUS Server IP Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="srv_ip_Aband" type="text" size="15" maxlength="15" /></span>
	</div>
	<div name="eap_Aband" class="textinput">
		<span class="name"><?echo i18n("Port");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="srv_port_Aband" type="text" size="5" maxlength="5" /></span>
	</div>
	<div name="eap_Aband" class="textinput">
		<span class="name"><?echo i18n("Shared Secret");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="srv_sec_Aband" type="password" size="50" maxlength="64" /></span>
	</div>
	<div class="gap"></div>
</div>

<p><input type="button" value="<?echo i18n("Save Settings");?>" onClick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onClick="BODY.OnReload();" /></p>

</form>
<div id="pad" style="display:none;">
	<div class="emptyline"></div>
	<div class="emptyline"></div>
	<div class="emptyline"></div>
	<div class="emptyline"></div>
	<div class="emptyline"></div>
	<div class="emptyline"></div>
	<div class="emptyline"></div>
	<div class="emptyline"></div>
	<div class="emptyline"></div>
</div>
