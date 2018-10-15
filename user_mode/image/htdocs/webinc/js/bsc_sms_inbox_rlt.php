<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: null,
	OnLoad: function()
	{
<?
		include "/htdocs/phplib/trace.php";
		$referer = "./bsc_sms_inbox.php?Treturn=1";
		$t = 2;

		$title	= i18n("SMS Inbox");
		$message= '"'.i18n("The device is quering the SMS now.").'"';

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
