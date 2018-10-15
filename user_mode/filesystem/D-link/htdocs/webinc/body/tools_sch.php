<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Schedules");?></h1>
	<p><?
		echo i18n('The Schedule configuration option is used to manage schedule rules for "WAN", "Wireless", "Virtual Server", "Port Forwarding", "Applications", "Network Filter", "Website Filter" and "Firewall".');
	?></p>
</div>
<div class="blackbox">
	<h2><span id="edittitle"><?=$SCH_MAX_COUNT?> -- <?echo i18n("Add Schedule Rule");?></span></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Name");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="schdesc" size="20" maxlength="16" type="text"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Day(s)");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input type="radio" id="schallweek" name="schdayselect"
				onclick="PAGE.OnClickSelectDays();"><?echo i18n("All Week");?>
			<input type="radio" id="schdays" name="schdayselect" checked="checked"
				onclick="PAGE.OnClickSelectDays();"><?echo i18n("Select Day(s)");?>
		</span>
	</div>
	<div class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">
			<input type="checkbox" id="schsun"><?echo i18n("Sun");?>
			<input type="checkbox" id="schmon"><?echo i18n("Mon");?>
			<input type="checkbox" id="schtue"><?echo i18n("Tue");?>
			<input type="checkbox" id="schwed"><?echo i18n("Wed");?>
			<input type="checkbox" id="schthu"><?echo i18n("Thu");?>
			<input type="checkbox" id="schfri"><?echo i18n("Fri");?>
			<input type="checkbox" id="schsat"><?echo i18n("Sat");?>
		</span>
	</div>
	<div class="gap"></div>
	<div class="gap"></div>
	<div class="textinput">
		<span class="name"><?echo i18n("All Day - 24 hrs");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input type="checkbox" id="sch24hrs" onclick="PAGE.OnClick24Hours();">
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Start Time");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input type="text" id="schstarthrs" size=3 maxlength=2
			>:<input type="text" id="schstartmin" size=3 maxlength=2>
			<select id="schstartapm">
				<option value="AM"><?echo i18n("AM");?></option>
				<option value="PM"><?echo i18n("PM");?></option>
			</select>
			(<?echo i18n("hour:minute, 12 hour time");?>)
		</span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("End Time");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input type="text" id="schendhrs" size=3 maxlength=2
			>:<input type="text" id="schendmin" size=3 maxlength=2>
			<select id="schendapm">
				<option value="AM"><?echo i18n("AM");?></option>
				<option value="PM"><?echo i18n("PM");?></option>
			</select>
			(<?echo i18n("hour:minute, 12 hour time");?>)
		</span>
	</div>
	<div class="centerline">
		<input type="button" id="schsubmit" value="<?echo i18n("Add");?>" onclick="PAGE.OnClickSchSubmit();">
		<input type="button" id="schcancel" value="<?echo i18n("Cancel");?>" onclick="PAGE.OnClickSchCancel();">
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Schedule Rules List");?></h2>
	<table id="schtable" class="general">
	<tr>
		<th width="151px"><?echo i18n("Name");?></th>
		<th width="201px"><?echo i18n("Day(s)");?></th>
		<th width="116px"><?echo i18n("Time Frame");?></th>
		<th width="20px"> </th>
		<th width="20px"> </th>
	</tr>
	</table>
	<div class="gap"></div>
</div>
<div class="emptyline"></div>
</form>
