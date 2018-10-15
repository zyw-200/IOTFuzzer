<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Application Rules");?></h1>
	<p>
		<?echo i18n('The Application Rules option is used to open single or multiple ports in your firewall when the router senses data sent to the Internet on an outgoing "Trigger" port or port range.')." ";
		echo i18n("Special Application rules apply to all computers on your internal network.");?>
	</p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?=$APP_MAX_COUNT?> -- <?echo i18n("Application Rules ");?></h2>
	<p><?echo i18n("Remaining number of rules that can be created");?>: <span id="rmd" style="color:red;"></span></p>
	<div class="centerline" align="center">
		<table id="" class="general">
		<tr>
			<th width="20px">&nbsp;</th>
			<th width="101px">&nbsp;</th>
			<th width="165px">&nbsp;</th>
			<th width="102px"><?echo i18n("Port");?></th>
			<th width="60px"><?echo i18n("Traffic Type");?></th>
			<th width="65px"><?echo i18n("Schedule");?></th>
		</tr>
<?
$INDEX = 1;
while ($INDEX <= $APP_MAX_COUNT) {	dophp("load", "/htdocs/webinc/body/adv_app_list.php");	$INDEX++; }
?>
		</table>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
