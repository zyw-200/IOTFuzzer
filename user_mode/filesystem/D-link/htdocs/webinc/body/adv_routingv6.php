<? include "/htdocs/webinc/body/draw_elements.php"; ?>
<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("ROUTING");?></h1>
	<p><?echo i18n("This Routing page allows you to specify custom routes that determine how data is moved around your network.");?></p>
	<p>	<input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>

<div class="blackbox">
    <h2><?=$ROUTING_MAX_COUNT?> -- <?echo i18n("ROUTE LIST");?></h2>

	<div class="centerline" align="center">
		<table id="" class="general">
<?
$INDEX = 1;
while ($INDEX <= $ROUTING_MAX_COUNT)	{dophp("load", "/htdocs/webinc/body/adv_routingv6_list.php");	$INDEX++;}
?>
		</table>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
