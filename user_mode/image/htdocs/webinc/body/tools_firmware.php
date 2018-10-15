<div class="orangebox">
	<h1><?echo i18n("Firmware Update");?></h1>
	<p>
		<?echo i18n("There may be new firmware for your router to improve functionality and performance.");?><br/>
		<a href="http://support.dlink.com.tw" target="_blank" style="color: #0000FF; text-decoration:underline;">
		<?echo i18n("Click here to check for an upgrade on our support site.");?>
		</a>
	</p>
	<p><?
		echo i18n("To upgrade the firmware, locate the upgrade file on the local hard drive with the Browse button. Once you have found the file to be used, click the Upload button to start the firmware upgrade.");
	?></p>
	<p><?
		echo i18n("The language pack allows you to change the language of the user interface on the router. We suggest that you upgrade your current language pack if you upgrade the firmware. This ensures that any changes in the firmware are displayed correctly.");
	?></p>
	<p><?
		echo i18n("To upgrade the language pack, locate the upgrade file on the local hard drive with the Browse button. Once you have found the file to be used, click the Upload button to start the language pack upgrade.");
	?></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Firmware Information");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Current Firmware Version");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/device/firmwareversion");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Current Firmware Date");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/device/firmwarebuilddate");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Check Online Now for Latest Firmware Version");?></span>
		<span class="delimiter">:</span>
		<span class="value"> <input type="button" id="chkfw_btn" value='<?echo i18n("Check Now");?>' onclick="PAGE.OnClickChkFW();" /> </span>
	</div>
	<br>
	<p id="fw_message" style="text-align:center; font-weight:bold; display:none;"></P>
</div>
<form id="fwup" action="fwup.cgi" method="post" enctype="multipart/form-data">
<div class="blackbox">
	<h2><?echo i18n("Firmware Upgrade");?></h2>
	<p class="strong" style="color:red;">
		<?echo i18n("Note: Some firmware upgrades reset the configuration options to the factory defaults. Before performing an upgrade, be sure to save the current configuration.");?>
	</p>
	<p class="strong" style="color:blue;">
		<?echo i18n("To upgrade the firmware, your PC must have a wired connection to the router. Enter the name of the firmware upgrade file, and click on the Upload button.");?>
	</p>
	<input type="hidden" name="REPORT_METHOD" value="301" />
	<input type="hidden" name="REPORT" value="tools_fw_rlt.php" />
	<input type="hidden" name="DELAY" value="10" />
	<input type="hidden" name="PELOTA_ACTION" value="fwupdate" />
	<div class="textinput">
		<span class="name"><?echo i18n("Upload");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input type="file" name="fw" size=40 />
			<input type="submit" value="<?echo i18n("Upload");?>" />
		</span>
	</div>
	<div class="emptyline"></div>
	<div class="gap"></div>
</div>
</form>
<form id="langup" action="tools_fw_rlt.php" method="post" enctype="multipart/form-data">
<div class="blackbox" <?if ($FEATURE_NOLANGPACK=="1") echo ' style="display:none;"';?> >
	<h2><?echo i18n("Language Pack Upgrade");?></h2>
	<input type="hidden" name="ACTION" value="langupdate" />
	<div class="textinput">
		<span class="name"><?echo i18n("Upload");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input type="file" name="sealpac" size=40 />
			<input type="submit" value="<?echo i18n("Upload");?>" />
		</span>
	</div>
	<div class="emptyline"></div>
	<div class="gap"></div>
</div>
</form>
<!--<div class="blackbox">
	<h2><?echo i18n("Firmware Upgrade Notification Options");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Automatically Check Online for Latest Firmware Version");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input type="checkbox" id="autochkfw" /></span>
	</div>
	<div class="gap"></div>
	<div class="textinput">
		<span class="name"><?echo i18n("Email Notification of Newer Firmware Version");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input type="checkbox" id="emailnewfw" /></span>
	</div>
	<div class="gap"></div>
	<div class="gap"></div>
</div>-->
