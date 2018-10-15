<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("WEBSITE FILTER");?></h1>
	<p>
		<?echo i18n("The Website Filter option allows you to set up a list of Web sites you would like to allow or deny through your network.");?>
	</p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?=$URL_MAX_COUNT?> -- <?echo i18n("WEBSITE FILTERING RULES");?></h2>
	<p><?echo i18n("Configure Website Filter below:");?></p>
	<div>	
		&nbsp;	
		<select id="url_mode">
			<option value="DISABLE"><?echo i18n("Turn OFF WEBSITE FILTERING");?></option>
			<option value="DROP"><?echo i18n("ALLOW computers access to ONLY these sites");?></option>
			<option value="ACCEPT"><?echo i18n("DENY computers access to ONLY these sites");?></option>
		</select>
	</div>
	<p><?echo i18n("Remaining number of rules that can be created");?>: <span id="rmd" style="color:red;"></span></p>
	<div class="centerline" align="center">
		<table id="" class="general">
		<tr  align="center">
			<td width="26px">&nbsp;</td>
			<td width="250px"><?echo i18n("Website URL");?></td>
			<?if ($FEATURE_NOSCH!="1"){echo '<td width="233px">'.i18n("Schedule").'</td>\n';}?>
		</tr>
<?
$INDEX = 1;
while ($INDEX <= $URL_MAX_COUNT) {       dophp("load", "/htdocs/webinc/body/adv_web_filter_list.php");   $INDEX++; }
?>
		</table>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
