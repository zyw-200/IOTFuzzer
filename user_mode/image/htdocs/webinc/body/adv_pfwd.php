<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Port Forwarding");?></h1>
	<p><?echo i18n("This option is used to open multiple ports or a range of ports in your router and redirect data through those ports to a single PC on your network.")." ".
		i18n("This feature allows you to enter ports in the format, Port Ranges (100-150).")." ".
		i18n("This option is only applicable to the INTERNET session.");?></p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onClick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onClick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?=$PFWD_MAX_COUNT?> -- <?echo i18n("Port Forwarding Rules");?></h2>
	<p><?echo i18n("Remaining number of rules that can be created");?>: <span id="rmd" style="color:red;"></span></p>
	<div class="centerline" align="center">
		<table id="" class="general">
		<tr>
			<th width="20px">&nbsp;</th>
			<th width="136px">&nbsp;</th>
			<th width="162px">&nbsp;</th>
			<th width="115px"><?echo i18n("Ports to Open");?></th>
			<?if ($FEATURE_NOSCH!="1"){echo '<th width="73px">&nbsp;</th>\n';}?>
		</tr>
<?
$INDEX = 1;
while ($INDEX <= $PFWD_MAX_COUNT) {	dophp("load", "/htdocs/webinc/body/adv_pfwd_list.php");	$INDEX++; }
?>
		</table>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onClick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onClick="BODY.OnReload();" /></p>
</form>
