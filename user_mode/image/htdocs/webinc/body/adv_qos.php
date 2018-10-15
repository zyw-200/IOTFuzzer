<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("QOS ENGINE");?></h1>
	<p><?echo i18n("Use this section to configure D-Link's Smart QoS. ");?>
	<?echo i18n("The QoS Engine improves your online gaming experience by ensuring that your game traffic is prioritized over other network traffic, such as FTP or Web. ");?>
	</p>
	<p>
		<input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" />
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("QOS ENGINE SETUP");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable QoS Engine");?></span>
		<span class="delimiter">:</span>
		<span class="value">
		<input id="en_qos" type="checkbox" onclick="PAGE.OnClickQosEnable();"/>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Automatic Uplink Speed");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="auto_speed" type="checkbox" onclick="PAGE.OnClickQosAuto()">
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Measured Uplink Speed");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_upstream"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Manual Uplink Speed");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="upstream" type="text" size=6 maxlength=6>
			kbps <span>&nbsp;&lt;&lt;&nbsp;</span>
			<select id="select_upstream" modified="ignore" onchange="PAGE.OnChangeQosUpstream(this.value);">
				<option value="0" selected><?echo i18n("Select Transmission Rate");?></option>
				<option value="128">128k</option>	
				<option value="256">256k</option>
				<option value="384">384k</option>
				<option value="512">512k</option>
				<option value="1024">1M</option>
				<option value="2048">2M</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Connection Type");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="conntype">
				<option value="AUTO"><?echo i18n("Auto-detect");?></option>
				<option value="ADSL"><?echo i18n("xDSL Or Other Frame Relay Network");?></option>
				<option value="CABLE"><?echo i18n("Cable Or Other Broadband Network");?></option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Detected xDSL or Other Frame Relay Network");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_type"></span>
	</div>
	<div class="emptyline"></div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
