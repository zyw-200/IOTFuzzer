<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: null,
	OnLoad: function()
	{
<?
		if ($_GET["RESULT"]=="SUCCESS")
		{
			$bt = query("/runtime/device/bootuptime");
			$delay = 15;
			$bt = $bt + $delay;
			echo '\t\tvar banner = "'.i18n("Restore Succeeded").'";';
			echo '\t\tvar msgArray = ["'.i18n("The restored configuration file has been uploaded successfully.").'"];';
			echo '\t\tvar sec = '.$bt.';';
			if ($_SERVER["SERVER_PORT"]=="80")
				echo '\t\tvar url = "http://'.$_SERVER["HTTP_HOST"].'/index.php";';
			else
				echo '\t\tvar url = "http://'.$_SERVER["HTTP_HOST"].':'.$_SERVER["SERVER_PORT"].'/index.php";';
			echo '\t\tBODY.ShowCountdown(banner, msgArray, sec, url);';
		}
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
