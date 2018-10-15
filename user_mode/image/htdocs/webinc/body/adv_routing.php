<form id="mainform"  onSubmit="return false;">
	<div class="orangebox">
    <h1><? echo i18n("Routing"); ?></h1>
    <p><? echo i18n("The Routing option allows you to define static routes to specific destinations. "); ?></p>
	<p>	
		<input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" />
	</p>
    </div>
    <div class="blackbox">
		<h2><?echo i18n($ROUTING_MAX_COUNT);?> -- <? echo  i18n("STATIC ROUTING"); ?></h2>
		<p><?echo i18n("Remaining number of rules that can be created");?>: <span id="rmd" style="color:red;"></span></p>
		<div class="centerline" align="center">
			<table id="" class="general" width=525>
			<tr class="centerline" align="center">
				<td width="20px" class=c_tb>&nbsp;</td>
				<td width="160px" class=c_tb><?echo i18n("Interface");?></td>
				<td class=c_tb><?echo i18n("Destination");?></td>
				<td class=c_tb><?echo i18n("Subnet Mask");?></td>
				<td class=c_tb><?echo i18n("Gateway");?></td>
			</tr>

<?
	$ROUTING_INDEX = 1;
	while ($ROUTING_INDEX <= $ROUTING_MAX_COUNT) {	dophp("load", "/htdocs/webinc/body/adv_routing_list.php");	$ROUTING_INDEX++; }
?>
			</table>
		</div>
	<div class="gap"></div>
	</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
