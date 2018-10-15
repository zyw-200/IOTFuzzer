<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: null,
	OnLoad: function() {},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result) { return false; },
	InitValue: function(xml) { return true; },
	PreSubmit: function() { return null; },
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	OnClickDownload: function()
	{
		OBJ("dlcfgbin").submit();
	},
	OnClickUpload: function()
	{
		if (OBJ("ulcfg").value=="")
		{
			BODY.ShowAlert("<?echo i18n("You must enter the name of a configuration file first.");?>");
			return false;
		}
		OBJ("ulcfgbin").submit();
	},
	OnClickFReset: function()
	{
		if (confirm("<?echo i18n("Are you sure you want to reset the device to its factory default settings?")."\\n".
					i18n("This will cause all current settings to be lost.");?>"))
		{
			Service("FRESET");
		}
	},
	OnClickReboot: function()
	{
		if (confirm("<?echo i18n("Are you sure you want to reboot the device?")."\\n".
					i18n("Rebooting will disconnect any active internet sessions.");?>"))
		{
			Service("REBOOT");
		}
	}
}

function Service(svc)
{	
	var banner = "<?echo i18n("Rebooting");?>...";
	var msgArray = ["<?echo i18n("If you changed the IP address of the router you will need to change the IP address in your browser before accessing the configuration web page again.");?>"];
	var delay = 10;
	var sec = <?echo query("/runtime/device/bootuptime");?> + delay;
	var url = null;
	var ajaxObj = GetAjaxObj("SERVICE");
	if (svc=="FRESET")		url = "http://192.168.0.1/index.php";
	else if (svc=="REBOOT")	url = "http://<?echo $_SERVER["HTTP_HOST"];?>/index.php";
	else					return false;
	ajaxObj.createRequest();
	ajaxObj.onCallback = function (xml)
	{
		ajaxObj.release();
		if (xml.Get("/report/result")!="OK")
			BODY.ShowAlert("Internal ERROR!\nEVENT "+svc+": "+xml.Get("/report/message"));
		else
			BODY.ShowCountdown(banner, msgArray, sec, url);
	}
	ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
	ajaxObj.sendRequest("service.cgi", "EVENT="+svc);
}

</script>
