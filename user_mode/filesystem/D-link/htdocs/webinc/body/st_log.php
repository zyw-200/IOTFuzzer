<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("View Log");?></h1>
	<p>
	<?echo i18n("The View Log displays the activities occurring on the")." ";echo query("/runtime/device/modelname");?>.
	</p>
	<div class="gap"></div>
	<p>
		<input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" />
	</p>
	<div class="gap"></div>
</div>

<div class="blackbox">
	<h2><?echo i18n("Save Log File");?></h2>
	<p><?echo i18n("Save Log File To Local Hard Drive.");?> <input name="save_log" value="<?echo i18n("Save");?>" onclick="window.location.href='/log_get.php';" type="button"/></p>
	<div class="gap"></div>
</div>

<div class="blackbox">
	<h2><?echo i18n("Log Type & Level");?></h2>
	<table cellpadding="2" cellspacing="1" width="525">
	<tbody>
	<tr>
		<td align="right"><?echo i18n("Log Type");?>:</td>
		<td align="left"><input type="radio" id="sysact" name="Type" onclick='PAGE.OnClickChangeType("sysact");' modified="ignore"><?echo i18n("System");?></td>
		<td align="left"><input type="radio" id="attack" name="Type" onclick='PAGE.OnClickChangeType("attack");' modified="ignore"><?echo i18n("Firewall &amp; Security");?></td>
		<td align="left"><input type="radio" id="drop" name="Type" onclick='PAGE.OnClickChangeType("drop");' modified="ignore"><?echo i18n("Router Status");?></td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td align="right"><?echo i18n("Log Level");?>:</td>
		<td align="left"><input type="radio" id="LOG_dbg" name="Level"><?echo i18n("Critical");?></td>
		<td align="left"><input type="radio" id="LOG_warn" name="Level"><?echo i18n("Warning");?></td>
		<td align="left"><input type="radio" id="LOG_info" name="Level"><?echo i18n("Information");?></td>
	</tr>
	</tbody></table>
	<div class="gap"></div>
</div>

<div class="blackbox">
	<h2><?echo i18n("Log Files");?></h2>
	<table cellpadding="1" cellspacing="1" border="0" width="525">
	<tr>
		<td align="left">
		<div>
			<input type=button value="<?echo i18n("First Page");?>" id="fp"  onclick="PAGE.OnClickToPage('1')">
			<input type=button value="<?echo i18n("Last Page");?>" id="lp"  onclick="PAGE.OnClickToPage('0')">
			<input type=button value="<?echo i18n("Previous");?>" id="pp"  onclick="PAGE.OnClickToPage('-1')">
			<input type=button value="<?echo i18n("Next");?>" id="np" onclick="PAGE.OnClickToPage('+1')">
			<input type=button value="<?echo i18n("Clear");?>" id="clear" onclick="PAGE.OnClickClear()">
			<input type=button value='<?echo i18n("Link To Email Log Settings");?>' onclick="javascript:self.location.href='tools_email.php'">
		</div>
		</td>
	</tr>
	<tr><td class=l_tb><div id="sLog"></div></td></tr>
	</table>
</div>
</form>
