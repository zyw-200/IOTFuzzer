<?
include "/htdocs/phplib/xnode.php";
?>
<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Virtual Server");?></h1>
	<p>
		<?echo i18n("The Virtual Server option allows you to define a single public port on your router for redirection to an internal LAN IP Address and Private LAN port if required.");?>
		<?echo i18n("This feature is useful for hosting online services such as FTP or Web Servers.");?>
	</p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?=$VSVR_MAX_COUNT?> - <?echo i18n("Virtual Servers List");?></h2>
	<p><?echo i18n("Remaining number of rules that can be created");?>: <span id="rmd" style="color:red;"></span></p>
	<div class="centerline" align="center">
		<table id="" class="general">
		<tr>
			<th width="20px">&nbsp;</th>
			<th width="130px">&nbsp;</th>
			<th width="170px">&nbsp;</th>
			<th width="45px"><?echo i18n("Port");?></th>
			<th width="76px"><?echo i18n("Traffic Type");?></th>
			<th width="70px"><?echo i18n("Schedule");?></th>
		</tr>
<?
$INDEX = 1;
while ($INDEX <= $VSVR_MAX_COUNT) {	dophp("load", "/htdocs/webinc/body/adv_vsvr_list.php");	$INDEX++; }
?>
		</table>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
