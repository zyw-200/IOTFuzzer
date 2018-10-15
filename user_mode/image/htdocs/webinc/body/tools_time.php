<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Time AND DATE");?></h1>
	<p><?echo i18n("The Time and Date Configuration option allows you to configure, update, and maintain the correct time on the internal system clock.")." ".
		i18n("From this section you can set the time zone you are in and set the NTP (Network Time Protocol) Server.")." ".
		i18n("Daylight Saving can also be configured to adjust the time when needed.");?></p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Time and Date Configuration");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Time");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_time"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Time Zone");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="timezone" class="tzselect" onclick="PAGE.OnClicktimezone(this.value)">
<?
				foreach ("/runtime/services/timezone/zone")
				echo '\t\t\t<option value="'.$InDeX.'">'.get("h","name").'</option>\n';
?>
			</select>
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Daylight Saving");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input type="checkbox"	id="daylight"/>&nbsp;
			<input type="button"	id="manual_sync" <? if(query("/runtime/device/langcode")!="en") echo 'style="width: 88%;"';?> value="<?echo i18n("Sync. your computer's time settings");?>" onclick="PAGE.onClickManualSync();">
		</span>
	</div>
	<p id=sync_pc_msg></P>
	<div class="gap"></div>
	<div class="gap"></div> 
</div>
<div class="blackbox">
	<h2><?echo i18n("Automatic Time and Date Configuration");?></h2>
	<p><input name="ntp_enable" id="ntp_enable" onclick="PAGE.OnClickNtpEnb();" type="checkbox">
					<?echo i18n("Automatically synchronize with D-Link's Internet time server");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("NTP Server Used");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<select id="ntp_server">
				<option value=""><?echo i18n("Select NTP Server");?></option>
				<option value="ntp1.dlink.com">ntp1.dlink.com</option>
				<option value="ntp.dlink.com.tw">ntp.dlink.com.tw</option>
			</select>
			<input id="ntp_sync" type="button" value="<?echo i18n("Update Now");?>" onclick="PAGE.OnClickNTPSync()">
		</span>
	</div>
	<p id=sync_msg></P>
	<div class="gap"></div>
</div>


<div class="blackbox">
	<h2><?echo i18n("Set the Time and Date Manually");?></h2>

	<table class="timebox">
	<tbody>
	<tr>
		<td><?echo i18n("Year");?></td>
		<td class="timebox_item">
		  <select id="year" onchange="PAGE.OnChangeYear()"><?

			$i=2001;
			while ($i<2012) { $i++; echo "<option value=".$i.">".$i."</option>\n"; }

		  ?></select>
		</td>
		<td><?echo i18n("Month");?></td>
		<td class="timebox_item">
			<select id="month" onchange="PAGE.OnChangeMonth()">
				<option value=1><? echo i18n("Jan"); ?></option>
				<option value=2><? echo i18n("Feb"); ?></option>
				<option value=3><? echo i18n("Mar"); ?></option>
				<option value=4><? echo i18n("Apr"); ?></option>
				<option value=5><? echo i18n("May"); ?></option>
				<option value=6><? echo i18n("Jun"); ?></option>
				<option value=7><? echo i18n("Jul"); ?></option>
				<option value=8><? echo i18n("Aug"); ?></option>
				<option value=9><? echo i18n("Sep"); ?></option>
				<option value=10><? echo i18n("Oct"); ?></option>
				<option value=11><? echo i18n("Nov"); ?></option>
				<option value=12><? echo i18n("Dec"); ?></option>
			</select>
		</td>
		<td><?echo i18n("Day");?></td>
		<td class="timebox_item">
			<select id="day"></select>
		</td>
	</tr>
	<tr>
		<td><?echo i18n("Hour");?></td>
		<td class="timebox_item">
			<select id="hour"><?
				$i=0;
				while ($i<24) { echo "<option value=".$i.">".$i."</option>\n"; $i++; }
			?></select>
		</td>
		<td><?echo i18n("Minute");?></td>
		<td class="timebox_item">
			<select id="minute"><?
				$i=0;
				while ($i<60) { echo "<option value=".$i.">".$i."</option>\n"; $i++; }
			?></select>
		</td>
		<td><?echo i18n("Second");?></td>
		<td class="timebox_item">
			<select id="second"<?
				$i=0;
				while ($i<60) { echo "<option value=".$i.">".$i."</option>\n"; $i++; }
			?></select>
		</td>
	</tr>
	</tbody>
	</table>
	<div class="gap"></div> 
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
