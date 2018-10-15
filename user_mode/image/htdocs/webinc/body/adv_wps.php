<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Wi-Fi Protected Setup");?></h1>
	<p><?echo i18n("Wi-Fi Protected Setup is used to easily add devices to a network using a PIN or button press.")." ".
		i18n("Devices must support Wi-Fi Protected Setup in order to be configured by this method.");?></p>
	<p><?echo i18n("If the PIN changes, the new PIN will be used in following Wi-Fi Protected Setup process.")." ".
		i18n("Clicking on ''Don't Save Settings'' button will not reset the PIN.");?></p>
	<p><?echo i18n("However, if the new PIN is not saved, it will get lost when the device reboots or loses power.");?></p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onClick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onClick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Wi-Fi Protected Setup");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="en_wps" type="checkbox" onClick="PAGE.OnClickEnWPS();" /></span>
	</div>

	<div class="textinput">
		<span class="name"><?echo i18n("WiFi Protected Setup");?></span>
		<span class="delimiter">:</span>
		<span id="wifi_info_str" class="value"></span>
	</div>
	
	<div class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">
			<input id="reset_cfg" type="button" value="<?echo i18n("Reset to Unconfigured");?>"
				onClick="PAGE.OnClickResetCfg();" />
		</span>
	</div>
	<div class="emptyline"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("PIN Settings");?></h2>
	<div class="textinput">
		<span class="name">PIN</span>
		<span class="delimiter">:</span>
		<span id="pin" class="value"></span>
	</div>
	<div class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">
			<input id="reset_pin" type="button" value="<?echo i18n("Reset PIN to Default");?>"
				onClick="PAGE.OnClickResetPIN();" />&nbsp;&nbsp;
			<input id="gen_pin" type="button" value="<?echo i18n("Generate New PIN");?>"
				onClick="PAGE.OnClickGenPIN();" />
		</span>
	</div>
	<div class="emptyline"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Add Wireless Station");?></h2>
	<div class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">
			<input id="go_wps" type="button" value="<?echo i18n("Connect your Wireless Device");?>"
				onClick='self.location.href="./wiz_wps.php";' />
		</span>
	</div>
	<div class="emptyline"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
