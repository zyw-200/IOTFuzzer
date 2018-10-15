<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("MAC Address Filter");?></h1>
	<p>
		<?echo i18n("The MAC (Media Access Controller) Address filter option is used to control network access based on the MAC Address of the network adapter.");?>
		<?echo i18n("A MAC address is a unique ID assigned by the manufacturer of the network adapter.");?>
		<?echo i18n("This feature can be configured to ALLOW or DENY network/Internet access.");?>
	</p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?=$MAC_FILTER_MAX_COUNT?> -- <?echo i18n("MAC Filtering Rules");?></h2>
	<p><?echo i18n("Configure MAC Filtering below:");?>
	
		<select id="mode">
			<option value="DISABLE"><?echo i18n("Turn MAC Filtering OFF");?></option>
			<option value="DROP"><?echo i18n("Turn MAC Filtering ON and ALLOW computers listed to access the network");?></option>
			<option value="ACCEPT"><?echo i18n("Turn MAC Filtering ON and DENY computers listed to access the network");?></option>
		</select>
	</p>
	<p><?echo i18n("Remaining number of rules that can be created");?>: <span id="rmd" style="color:red;"></span></p>
	<div class="centerline" align="center">
		<table id="" class="general">
		<tr  align="center">
			<td width="23px">&nbsp;</td>
			<td width="133px"><?echo i18n("MAC Address");?></td>
			<td width="29px">&nbsp;</td>
			<td width="136px"><?echo i18n("DHCP Client List");?></td>
			
			<?if ($FEATURE_NOSCH!="1"){echo '<td width="188px">'.i18n("Schedule").'</td>\n';}?>
		</tr>
<?
$INDEX = 1;
while ($INDEX <= $MAC_FILTER_MAX_COUNT) {	dophp("load", "/htdocs/webinc/body/adv_mac_filter_list.php");	$INDEX++; }
?>
		</table>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
