<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Advanced Network Settings");?></h1>
	<p><?echo i18n("These options are for users that wish to change the LAN settings. We do not recommend changing these settings from factory default. ");?>
	<?echo i18n("Changing these settings may affect the behavior of your network.");?>
	</p>
	<p>
		<input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" />
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("UPNP");?></h2>
	<p><?echo i18n("Universal Plug and Play(UPnP) supports peer-to-peer Plug and Play functionality for network devices.");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable UPnP");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="upnp" value="" type="checkbox"/></span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("WAN Ping");?></h2>
	<p><?echo i18n("If you enable this feature, the WAN port of your router will respond to ping requests from the Internet that are sent to the WAN IP Address.");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable WAN Ping Response");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="icmprsp" value="" type="checkbox"/></span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("WAN Port Speed");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("WAN Port Speed");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wanspeed">
				<option value="1"><?echo i18n("10Mbps");?></option>
				<option value="2"><?echo i18n("100Mbps");?></option>
				<option value="0"><?echo i18n("Auto 10/100Mbps");?></option>
			</select>
		</span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Multicast Streams");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Multicast Streams");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="mcast" type="checkbox" /></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Enhance Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="enhance" type="checkbox" /></span>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
