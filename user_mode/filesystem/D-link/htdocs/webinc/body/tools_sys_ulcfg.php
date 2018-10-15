<?
if ($_GET["RESULT"]=="SUCCESS")
{
	$f_style = ' style="display:none;"';
	event('REBOOT');
}
else
{
	$f_style = ' style="display:block;"';
}
?><div class="orangebox" <?=$f_style?>>
	<h1><?echo i18n("Restore Invalid");?></h1>
	<p><?echo i18n("The restored configuration file is not correct.")." ".
		i18n("You may have restored a file that is not intended for this device, or the restore file is from an incompatible version of this product, or the restored file may be corrupted.");?></p>
	<p><?echo i18n("Try the restore again with valid restore configuration file.");?></p>
	<p><?echo i18n("Please press the button below to continue configuring the router.");?></p>
	<p><input type="button" value="<?echo i18n("Continue");?>" onclick="history.back();" /></p>
</div>
