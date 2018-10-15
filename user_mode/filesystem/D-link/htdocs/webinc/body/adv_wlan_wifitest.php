<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Advanced Wireless settings");?></h1>
	<p><?echo i18n("These options are for users that wish to change the behavior of their 802.11n wireless radio from the standard settings.")." ".
		i18n("We do not recommend changing these settings from the factory defaults.")." ".
		i18n("Incorrect settings may impact the performance of your wireless radio.")." ".
		i18n("The default settings should provide the best wireless radio performance in most environments.");?></p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onClick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onClick="BODY.OnReload();" /></p>
</div>

<!-- ===================== 2.4Ghz, BG band ============================== -->
<div class="blackbox">
	<h2><?echo i18n("Advanced Wireless Settings");?></h2>
	
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Band");?></span>
		<span class="delimiter">:</span>
		<span class="value"><b><?echo i18n("2.4GHz Band");?></b></span>
	</div>
	
	<div class="textinput">
		<span class="name"><?echo i18n("Transmit Power");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="tx_power">
				<option value="100">100%</option>
				<option value="50">50%</option>
				<option value="25">25%</option>
				<option value="12.5">12.5%</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Beacon interval");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="beacon" type="text" size="4" maxlength="4" />
			(<?echo i18n("msec").", ".i18n("range").": 20~1000, ".i18n("default");?>: 100)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("RTS Threshold");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="rts" type="text" size="4" maxlength="4" />
			(<?echo i18n("range").": 256~2346, ".i18n("default");?>: 2346)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Fragmentation");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="frag" type="text" size="4" maxlength="4" />
			(<?echo i18n("range").": 1500~2346, ".i18n("default").": 2346, ".i18n("even number only");?>)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("DTIM interval");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="dtim" type="text" size="4" maxlength="3" />
			(<?echo i18n("range").": 1~255, ".i18n("default");?>: 1)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Preamble Type");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="preamble" name="preamble" type="radio" value="short" /><?echo i18n("Short Preamble");?>
			<input id="preamble" name="preamble" type="radio" value="long" /><?echo i18n("Long Preamble");?>
		</span>
	</div>
<!--	<div class="textinput">
		<span class="name"><?echo i18n("CTS Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input name="cts" type="radio" disabled /><?echo i18n("None");?>
			<input name="cts" type="radio" disabled /><?echo i18n("Always");?>
			<input name="cts" type="radio" disabled /><?echo i18n("Auto");?>
		</span>
	</div>-->
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wlan_mode" onChange="PAGE.OnChangeWLMode();">
				<option value="n">802.11n only</option>
				<option value="g">802.11g only</option>
				<option value="bg">802.11 Mixed(g/b)</option>
				<option value="bgn">802.11 Mixed(n/g/b)</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Band Width");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="bw">
				<option value="20">20 MHz</option>
				<option value="20+40">20/40 MHz(<?echo i18n("Auto");?>)</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Short Guard Interval");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="sgi" type="checkbox" /></span>
	</div>
	<div class="gap"></div>
</div>



<!-- ===================== 5Ghz, A band ============================== -->
<div class="blackbox">
	<h2><?echo i18n("Advanced Wireless Settings");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Band");?></span>
		<span class="delimiter">:</span>
		<span class="value"><b><?echo i18n("5GHz Band");?></b></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Transmit Power");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="tx_power_Aband">
				<option value="100">100%</option>
				<option value="50">50%</option>
				<option value="25">25%</option>
				<option value="12.5">12.5%</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Beacon interval");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="beacon_Aband" type="text" size="4" maxlength="4" />
			(<?echo i18n("msec").", ".i18n("range").": 20~1000, ".i18n("default");?>: 100)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("RTS Threshold");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="rts_Aband" type="text" size="4" maxlength="4" />
			(<?echo i18n("range").": 256~2346, ".i18n("default");?>: 2346)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Fragmentation");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="frag_Aband" type="text" size="4" maxlength="4" />
			(<?echo i18n("range").": 1500~2346, ".i18n("default").": 2346, ".i18n("even number only");?>)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("DTIM interval");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="dtim_Aband" type="text" size="4" maxlength="3" />
			(<?echo i18n("range").": 1~255, ".i18n("default");?>: 1)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Preamble Type");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="preamble_Aband" name="preamble_Aband" type="radio" value="short" /><?echo i18n("Short Preamble");?>
			<input id="preamble_Aband" name="preamble_Aband" type="radio" value="long" /><?echo i18n("Long Preamble");?>
		</span>
	</div>

	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="wlan_mode_Aband" onChange="PAGE.OnChangeWLMode();">
				<option value="a">802.11a only</option>
				<option value="n">802.11n only</option>
				<option value="an">802.11 Mixed(a/n)</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Band Width");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="bw_Aband">
				<option value="20">20 MHz</option>
				<option value="20+40">20/40 MHz(<?echo i18n("Auto");?>)</option>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Short Guard Interval");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="sgi_Aband" type="checkbox" /></span>
	</div>
	<div class="gap"></div>
</div>


<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
