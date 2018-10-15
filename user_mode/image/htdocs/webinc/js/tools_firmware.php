<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "INET.WAN-1,RUNTIME.PHYINF",
	OnLoad: function() {},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result) { return false; },
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var wan	= PXML.FindModule("INET.WAN-1");
		var rphy = PXML.FindModule("RUNTIME.PHYINF");
		var rwanphyp = GPBT(rphy+"/runtime", "phyinf", "uid", XG(wan+"/inf/phyinf"), false);
		if(XG(rwanphyp+"/linkstatus")=="")	{ OBJ("chkfw_btn").disabled = true; }
		
		return true;
	},
	PreSubmit: function() { return null; },
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	OnClickChkFW: function()
	{
		// TO DO!
		OBJ("fw_message").style.display="block";
		OBJ("fw_message").innerHTML = "<?echo i18n("Connecting with the server for firmware information");?> ...";
		setTimeout('PAGE.GetCheckReport()',1000);
	},
	GetCheckReport: function()
	{
		OBJ("fw_message").innerHTML = "<?echo i18n("This firmware is the latest version.");?>";
	}
}
</script>
