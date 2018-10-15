<? include "/htdocs/webinc/body/draw_elements.php"; ?>
<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("FIREWALL & DMZ SETTINGS");?></h1>
	<p><?echo i18n("Firewall rules can be used to allow or deny traffic passing through the router.")." ".
		i18n("You can specify a single port by utilizing the input box at the top or a range of ports by utilizing both input boxes.");?></p>
	<p><?echo i18n('DMZ means "Demilitarized Zone".')." ".
		i18n('DMZ allows computers behind the router firewall to be accessible to Internet traffic.');?>
	<?echo i18n("Typically, your DMZ would contain Web servers, FTP servers and others.");?></p>
	<p>	<input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Firewall Settings");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable SPI");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="spi" type="checkbox"/></span>
	</div>
	<div class="gap"></div>
</div>

<div class="blackbox">
	<h2><?echo i18n("DMZ Host");?></h2>
	<p>
		<?echo i18n("The DMZ (Demilitarized Zone) option lets you set a single computer on your network outside of the router.");?>
		<?echo i18n("If you have a computer that cannot run Internet applications successfully from behind the router, then you can place the computer into the DMZ for unrestricted Internet access.");?>
	</p>
	<p>
		<strong><?echo i18n("Note");?>:</strong>
		<?echo i18n("Putting a computer in the DMZ may expose that computer to a variety of security risks.")." ".
			i18n("Use of this option is only recommended as a last resort.");?>
	</p>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable DMZ");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input type="checkbox" id="dmzenable" onclick="PAGE.OnClickDMZEnable();"/></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("DMZ IP Address");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="dmzhost" size="20" maxlength="15" value="0.0.0.0" type="text"/>
			<input modified="ignore" id="dmzadd" value="<<" onclick="PAGE.OnClickDMZAdd();" type="button"/>
		</span>
	</div>
	<div class="textinput">
		<span class="name"></span>
		<span class="delimiter"></span>
		<span class="value">
			<? DRAW_select_dhcpclist("LAN-1","hostlist", i18n("Computer Name"), "", "", "1", "selectSty"); ?>
		</span>
	</div>
	<div class="gap"></div>
</div>

<div class="blackbox">
	<h2><?=$FW_MAX_COUNT?> -- <?echo i18n("Firewall Rules");?></h2>
	<p><?echo i18n("Remaining number of rules that can be created");?>: <span id="rmd" style="color:red;"></span></p>
	<div class="centerline" align="center">
		<table id="" class="general">
		<tr>
			<th width="25px">&nbsp;</th>
			<th width="73px">&nbsp;</th>
			<th width="78px"><?echo i18n("Interface");?></th>
			<th width="133px"><?echo i18n("IP Address");?></th>
			<th width="77px">&nbsp;</th>
			<?if ($FEATURE_NOSCH!="1")	echo '<th width="118px"><?echo i18n("Schedule");?></th>\n';?>
		</tr>
<?
$INDEX = 1;
while ($INDEX <= $FW_MAX_COUNT)	{dophp("load", "/htdocs/webinc/body/adv_firewall_list.php");	$INDEX++;}
?>
		</table>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>

