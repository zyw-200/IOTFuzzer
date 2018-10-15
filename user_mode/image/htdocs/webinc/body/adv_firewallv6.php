<? include "/htdocs/webinc/body/draw_elements.php"; ?>
<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("IPv6 FIREWALL");?></h1>
	<p><?echo i18n("The firewall settings section is an advance feature used to allow or deny traffic from passing through the device.")." ".
		i18n("It works in the same way as IP Filters with additional settings.")." ".
		i18n("You can create more detailed rules for the device.");?></p>
	<p>	<input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>

<div class="blackbox">
    <h2><?=$FW_MAX_COUNT?> -- <?echo i18n("IPv6 FIREWALL RULES");?></h2>
    <p><?echo i18n("Remaining number of rules that can be created");?>: <span id="rmd" style="color:red;"></span></p>
    <p><?echo i18n("Configure IPv6 Filtering below:");?><br>
		<select id="mode">
			<option value="DISABLE"><?echo i18n("Turn IPv6 Filtering OFF");?></option>
			<option value="DROP"><?echo i18n("Turn IPv6 Filtering ON and ALLOW rules listed");?></option>
			<option value="ACCEPT"><?echo i18n("Turn IPv6 Filtering ON and DENY rules listed");?></option>
		</select>
	</p>

	<div class="centerline" align="center">
		<table id="" class="general">
<?
$INDEX = 1;
while ($INDEX <= $FW_MAX_COUNT)	{dophp("load", "/htdocs/webinc/body/adv_firewallv6_list.php");	$INDEX++;}
?>
		</table>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
