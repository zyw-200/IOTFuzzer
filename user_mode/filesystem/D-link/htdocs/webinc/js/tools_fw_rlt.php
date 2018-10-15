<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: null,
	OnLoad: function()
	{
<?
		include "/htdocs/phplib/trace.php";
		$referer = $_SERVER["HTTP_REFERER"];
		$t = 0;

		if ($_GET["PELOTA_ACTION"]=="fwupdate")
		{
			if ($_GET["RESULT"]=="SUCCESS")
			{
				$size	= fread("j","/var/session/imagesize"); if ($size == "") $size = "4000000";
				$fptime	= query("/runtime/device/fptime");
				$bt		= query("/runtime/device/bootuptime");
				$delay	= 10;
				$t		= $size/64000*$fptime/1000+$bt+$delay+20;
				$title	= i18n("Firmware Upload");
				$message= '"'.i18n("The device is updating the firmware now.").'", '.
						  '"'.i18n("It takes a while to update firmware and reboot the device.").
						  ' '.i18n("Please DO NOT power off the device.").'"';
			}
			else
			{
				$title = i18n("Firmware Upload Fail");
				$btn = "'<input type=\"button\" value=\"".i18n("Continue")."\" onclick=\"self.location=\\'tools_firmware.php\\';\">'";
				if ($_GET["REASON"]=="ERR_NO_FILE")
				{
					$message = "'".i18n("No image file.")." ".i18n("Please select the correct image file and upload it again.")."', ".$btn;
				}
				else if ($_GET["REASON"]=="ERR_INVALID_SEAMA" || $_GET["REASON"]=="ERR_INVALID_FILE")
				{
					$message = "'".i18n("Invalid image file.")." ".i18n("Please select the correct image file and upload it again.")."', ".$btn;
				}
				else if ($_GET["REASON"]=="ERR_UNAUTHORIZED_SESSION")
				{
					$message = "'".i18n("You are unauthorized or authority limited.")." ".i18n("Please Login first.")."', ".$btn;
				}
				else if ($_GET["REASON"]=="ERR_ANOTHER_FWUP_PROGRESS")
				{
					$message = "'".i18n("Another image update process is progressing.")." ".i18n("If you still want to update the image, please wait until the other process is done and try it again.")."', ".$btn;
				}
			}
		}
		else if ($_POST["ACTION"]=="langupdate")
		{
			TRACE_debug("ACTION=".$_POST["ACTION"]);
			TRACE_debug("FILE=".$_FILES["sealpac"]);
			TRACE_debug("FILETYPES=".$_FILETYPES["sealpac"]);
			$slp = "/var/sealpac/sealpac.slp";
			$title = i18n("Update Language Pack");
			if ($_FILES["sealpac"]=="")
			{
				$title = i18n("Language Pack Upload Fail");
				$message = "'".i18n("The language pack image is invalid.")."', ".
							"'<a href=\"".$referer."\">".i18n("Click here to return to the previous page.")."</a>'";
			}
			else if (fcopy($_FILES["sealpac"], $slp)!="1")
			{
				$title = i18n("Language Pack Upload Fail");
				$message = "'INTERNAL ERROR: fcopy() return error!'";
			}
			else
			{			
				$langcode = sealpac($slp);
				if ($langcode != "")
				{
					$message = "'".i18n("You have installed the language pack ($1) successfully.",$langcode)."', ".
								"'<a href=\"".$referer."\">".i18n("Click here to return to the previous page.")."</a>'";
					fwrite(w, "/var/sealpac/langcode", $langcode);
					set("/runtime/device/langcode", $langcode);
					event("SEALPAC.SAVE");
				}
				else
				{
					$title = i18n("Language Pack Upload Fail");
					$message = "'".i18n("The language pack image is invalid.")."', ".
								"'<a href=\"".$referer."\">".i18n("Click here to return to the previous page.")."</a>'";
					unlink($slp);
				}
			}
		}
		else if ($_POST["ACTION"]=="langclear")
		{
			$title = i18n("Clear Language Pack");
			$message = "'".i18n("Clearing the language pack ...")."', ".
						"'<a href=\"".$referer."\">".i18n("Click here to return to the previous page.")."</a>'";
			set("/runtime/device/langcode", "en");
			event("SEALPAC.CLEAR");
		}
		else
		{
			$title = i18n("Unknown action - ").$_POST["ACTION"];
			$message = "'<a href=\"./index.php\">".i18n("Click here to redirect to the home page now.")."</a>'";
			$referer = "./index.php";
		}

		echo "\t\tvar msgArray = [".$message."];\n";
		if ($t > 0)
			echo "\t\tBODY.ShowCountdown(\"".$title."\", msgArray, ".$t.", \"".$referer."\");\n";
		else
			echo "\t\tBODY.ShowMessage(\"".$title."\", msgArray);\n";
?>	},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result) { return true; },
	InitValue: function(xml) { return true; },
	PreSubmit: function() { return null; },
	IsDirty: null,
	Synchronize: function() {}
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
}
</script>
